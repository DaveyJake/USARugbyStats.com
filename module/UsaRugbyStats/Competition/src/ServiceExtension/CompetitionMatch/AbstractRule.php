<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use UsaRugbyStats\Application\Service\ServiceExtensionInterface;

abstract class AbstractRule implements ServiceExtensionInterface
{
    protected $detailsFields = [
        'match.date',
        'match.location',
        'match.locationDetails',
        'match.status',
    ];

    protected function enableMatchChange($context)
    {
        $context->flags->{'match'}->andWith(true);

        return $context->flags->{'match'}->is_on();
    }

    protected function disableMatchChange($context)
    {
        $context->flags->{'match'}->off();

        return $context->flags->{'match'}->is_off();
    }

    protected function enableDetailsChange($context)
    {
        $result = true;
        foreach ($this->detailsFields as $key) {
            $context->flags->$key->andWith(true);
            $result = $result && $context->flags->$key->is_on();
        }

        return $result;
    }

    protected function disableDetailsChange($context)
    {
        $result = true;
        foreach ($this->detailsFields as $key) {
            $context->flags->$key->off();
            $result = $result && $context->flags->$key->is_off();
        }

        return $result;
    }

    protected function enableTeamChange($context)
    {
        return $this->enableSideATeamChange($context)
            && $this->enableSideHTeamChange($context);
    }

    protected function disableTeamChange($context)
    {
        return $this->disableSideATeamChange($context)
            && $this->disableSideHTeamChange($context);
    }

    protected function enableTeamRosterChange($context)
    {
        return $this->enableSideATeamRosterChange($context)
            && $this->enableSideHTeamRosterChange($context);
    }

    protected function showTeamRoster($context)
    {
        return $this->showSideATeamRoster($context)
            && $this->showSideHTeamRoster($context);
    }

    protected function disableTeamRosterChange($context)
    {
        return $this->disableSideATeamRosterChange($context)
            && $this->disableSideHTeamRosterChange($context);
    }

    protected function hideTeamRoster($context)
    {
        return $this->hideSideATeamRoster($context)
            && $this->hideSideHTeamRoster($context);
    }

    protected function enableTeamEventsChange($context)
    {
        return $this->enableSideATeamEventsChange($context)
            && $this->enableSideHTeamEventsChange($context);
    }

    protected function showTeamEvents($context)
    {
        return $this->showSideATeamEvents($context)
            && $this->showSideHTeamEvents($context);
    }

    protected function disableTeamEventsChange($context)
    {
        return $this->disableSideATeamEventsChange($context)
            && $this->disableSideHTeamEventsChange($context);
    }

    protected function hideTeamEvents($context)
    {
        return $this->hideSideATeamEvents($context)
            && $this->hideSideHTeamEvents($context);
    }

    protected function enableSideHTeamChange($context)
    {
        $context->flags->{'match.teams.H.team'}->andWith(true);

        return $context->flags->{'match.teams.H.team'}->is_on();
    }

    protected function disableSideHTeamChange($context)
    {
        $context->flags->{'match.teams.H.team'}->off();

        return $context->flags->{'match.teams.H.team'}->is_off();
    }

    protected function enableSideHTeamRosterChange($context)
    {
        $context->flags->{'match.teams.H.players'}->andWith(true);

        return $context->flags->{'match.teams.H.players'}->is_on();
    }

    protected function showSideHTeamRoster($context)
    {
        $context->flags->{'match.teams.H.players%visible'}->on();

        return $context->flags->{'match.teams.H.players%visible'}->is_on();
    }

    protected function disableSideHTeamRosterChange($context)
    {
        $context->flags->{'match.teams.H.players'}->off();

        return $context->flags->{'match.teams.H.players'}->is_off();
    }

    protected function hideSideHTeamRoster($context)
    {
        $context->flags->{'match.teams.H.players%visible'}->off();

        return $context->flags->{'match.teams.H.players%visible'}->is_off();
    }

    protected function enableSideHTeamEventsChange($context)
    {
        $context->flags->{'match.teams.H.events'}->andWith(true);

        return $context->flags->{'match.teams.H.events'}->is_on();
    }

    protected function showSideHTeamEvents($context)
    {
        $context->flags->{'match.teams.H.events%visible'}->on();

        return $context->flags->{'match.teams.H.events%visible'}->is_on();
    }

    protected function disableSideHTeamEventsChange($context)
    {
        $context->flags->{'match.teams.H.events'}->off();

        return $context->flags->{'match.teams.H.events'}->is_off();
    }

    protected function hideSideHTeamEvents($context)
    {
        $context->flags->{'match.teams.H.events%visible'}->off();

        return $context->flags->{'match.teams.H.events%visible'}->is_off();
    }

    protected function enableSideATeamChange($context)
    {
        $context->flags->{'match.teams.A.team'}->andWith(true);

        return $context->flags->{'match.teams.A.team'}->is_on();
    }

    protected function disableSideATeamChange($context)
    {
        $context->flags->{'match.teams.A.team'}->off();

        return $context->flags->{'match.teams.A.team'}->is_off();
    }

    protected function enableSideATeamRosterChange($context)
    {
        $context->flags->{'match.teams.A.players'}->andWith(true);

        return $context->flags->{'match.teams.A.players'}->is_on();
    }

    protected function showSideATeamRoster($context)
    {
        $context->flags->{'match.teams.A.players%visible'}->on();

        return $context->flags->{'match.teams.A.players%visible'}->is_on();
    }

    protected function disableSideATeamRosterChange($context)
    {
        $context->flags->{'match.teams.A.players'}->off();

        return $context->flags->{'match.teams.A.players'}->is_off();
    }

    protected function hideSideATeamRoster($context)
    {
        $context->flags->{'match.teams.A.players%visible'}->off();

        return $context->flags->{'match.teams.A.players%visible'}->is_off();
    }

    protected function enableSideATeamEventsChange($context)
    {
        $context->flags->{'match.teams.A.events'}->andWith(true);

        return $context->flags->{'match.teams.A.events'}->is_on();
    }

    protected function showSideATeamEvents($context)
    {
        $context->flags->{'match.teams.A.events%visible'}->on();

        return $context->flags->{'match.teams.A.events%visible'}->is_on();
    }

    protected function disableSideATeamEventsChange($context)
    {
        $context->flags->{'match.teams.A.events'}->off();

        return $context->flags->{'match.teams.A.events'}->is_off();
    }

    protected function hideSideATeamEvents($context)
    {
        $context->flags->{'match.teams.A.events%visible'}->off();

        return $context->flags->{'match.teams.A.events%visible'}->is_off();
    }

    protected function enableSignatureChange($context)
    {
        $context->flags->{'match.signatures'}->andWith(true);

        return $context->flags->{'match.signatures'}->is_on();
    }

    protected function showSignatures($context)
    {
        $context->flags->{'match.signatures%visible'}->on();

        return $context->flags->{'match.signatures%visible'}->is_on();
    }

    protected function disableSignatureChange($context)
    {
        $context->flags->{'match.signatures'}->off();

        return $context->flags->{'match.signatures'}->is_off();
    }

    protected function hideSignatures($context)
    {
        $context->flags->{'match.signatures%visible'}->off();

        return $context->flags->{'match.signatures%visible'}->is_off();
    }
}
