<?php
namespace UsaRugbyStats\Competition\DataImporter;

use UsaRugbyStats\DataImporter\Task\TaskInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use UsaRugbyStats\Competition\Service\CompetitionService;
use UsaRugbyStats\Competition\Entity\Competition;

class ImportCompetitionsTask implements TaskInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $svcCompetition;

    public function __construct(CompetitionService $svcCompetition)
    {
        $this->svcCompetition = $svcCompetition;
    }

    public function execute(array $data)
    {
        $this->getLogger()->debug('Importing Competition records...');

        // Allow manually setting the record identifer
        $metadata = $this->svcCompetition->getObjectManager()->getClassMetadata('UsaRugbyStats\Competition\Entity\Competition');
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        foreach ($data as $competition) {
            $this->getLogger()->debug(" - {$competition['name']}");

            $session = $this->svcCompetition->startSession();
            $session->form = clone $this->svcCompetition->getCreateForm();
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
