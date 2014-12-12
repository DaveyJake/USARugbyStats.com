<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\View\Helper\AbstractHelper;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class CompetitionMatchStatus extends AbstractHelper
{
    public function __invoke(Match $match)
    {
        switch ( $match->getStatus() ) {
            case Match::STATUS_AWAYFORFEIT:
                return 'Away Forfeit';
            case Match::STATUS_CANCELLED:
                return 'Cancelled';
            case Match::STATUS_FINISHED:
                return 'Finished';
            case Match::STATUS_HOMEFORFEIT:
                return 'Home Forfeit';
            case Match::STATUS_NOTSTARTED:
                return 'Not Started';
            case Match::STATUS_STARTED:
                return 'Started';
        }

        return 'Unknown';
    }
}
