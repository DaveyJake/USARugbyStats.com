<?php
namespace UsaRugbyStats\Competition\DataImporter;

use UsaRugbyStats\DataImporter\Task\TaskInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use UsaRugbyStats\Competition\Service\CompetitionService;
use UsaRugbyStats\Competition\Entity\Competition;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImportCompetitionsTask implements TaskInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $svcCompetition;
    protected $serviceLocator;

    public function __construct(CompetitionService $svcCompetition, ServiceLocatorInterface $sl)
    {
        $this->svcCompetition = $svcCompetition;
        $this->serviceLocator = $sl;
    }

    public function execute(array $data)
    {
        $this->getLogger()->debug('Importing Competition records...');

        $digitFilter = new \Zend\Filter\Digits();

        // Allow manually setting the record identifer
        $metadata = $this->svcCompetition->getObjectManager()->getClassMetadata('UsaRugbyStats\Competition\Entity\Competition');
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        foreach ($data as $competition) {
            $this->getLogger()->debug(" - {$competition['name']}");

            // Process Team Memberships if provided
            if ( isset($competition['teams']) && is_scalar($competition['teams']) ) {
                $competition['divisions'] = array();

                $divisions = explode(';', $competition['teams']);
                foreach ($divisions as $pairKey => $divisionData) {
                    if ( strstr($divisionData, '=') !== false ) {
                        list($divName,$divTeamList) = explode('=', $divisionData);
                    } else {
                        $divName = $pairKey;
                        $divTeamList = $divisionData;
                    }

                    if ( is_numeric($divName) ) {
                        $divName = "Division " . ($divName+1);
                    }
                    $divTeams = explode(',', $divTeamList);
                    $teamMemberships = array();
                    foreach ($divTeams as $teamid) {
                        $teamid = $digitFilter->filter($teamid);
                        if ( ! empty($teamid) ) {
                            array_push($teamMemberships, [
                                'id' => NULL,
                                'team' => $teamid
                            ]);
                        }
                    }
                    if ( empty($teamMemberships) ) {
                        continue;
                    }

                    array_push($competition['divisions'], [
                        'id' => NULL,
                        'name' => $divName,
                        'teamMemberships' => $teamMemberships,
                    ]);
                }
            }
            unset($competition['teams']);

            $session = $this->svcCompetition->startSession();
            $session->form = $this->serviceLocator->get('usarugbystats_competition_competition_createform');
            $session->entity = new Competition();
            $this->svcCompetition->prepare();

            $entity = $this->svcCompetition->create(['competition' => $competition]);
            if (! $entity instanceof Competition) {
                $this->getLogger()->crit(sprintf(
                    "ERROR: Failed to create competition: %s (Message: %s)",
                    $competition['name'],
                    var_export($session->form->getMessages(), true)
                ));
                continue;
            }
            unset($entity);
        }
    }
}
