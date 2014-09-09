<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;

class ToggleSectionsBasedOnMatchStatus extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $status = isset($e->getParams()->entity)
            ? $e->getParams()->entity->getStatus()
            : 'NS';

        switch ($status) {
            case 'NS':
                $this->enableTeamChange($e->getParams());
                $this->enableTeamRosterChange($e->getParams());
                $this->disableTeamEventsChange($e->getParams());
                $this->hideTeamEvents($e->getParams());
                $this->disableSignatureChange($e->getParams());
                $this->hideSignatures($e->getParams());
                break;
            case 'S':
                $this->disableTeamChange($e->getParams());
                $this->enableTeamRosterChange($e->getParams());
                $this->enableTeamEventsChange($e->getParams());
                $this->disableSignatureChange($e->getParams());
                $this->hideSignatures($e->getParams());
                break;
            case 'F':
            case 'HF':
            case 'AF':
            case 'C':
                $this->disableTeamChange($e->getParams());
                $this->disableTeamRosterChange($e->getParams());
                $this->disableTeamEventsChange($e->getParams());
                $this->enableSignatureChange($e->getParams());
                break;
        }
    }
}
