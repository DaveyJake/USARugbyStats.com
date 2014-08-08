<?php
namespace UsaRugbyStats\Competition\DataImporter;

use UsaRugbyStats\DataImporter\Task\TaskInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use UsaRugbyStats\Competition\Service\TeamService;
use UsaRugbyStats\Competition\Entity\Team;

class ImportTeamsTask implements TaskInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $svcTeam;

    public function __construct(TeamService $svcTeam)
    {
        $this->svcTeam = $svcTeam;
    }

    public function execute(array $data)
    {
        $this->getLogger()->debug('Importing Team records...');

        foreach ($data as $team) {
            $this->getLogger()->debug(" - {$team['name']}");

            $session = $this->svcTeam->startSession();
            $session->form = clone $this->svcTeam->getCreateForm();
            $session->entity = new Team();
            $this->svcTeam->prepare();

            $entity = $this->svcTeam->create(['team' => $team]);
            if (! $entity instanceof Team) {
                $this->getLogger()->crit("ERROR: Failed to create team: " . $team['name']);
                continue;
            }
            unset($entity);
        }
    }
}
