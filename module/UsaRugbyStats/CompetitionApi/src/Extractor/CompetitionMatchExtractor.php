<?php
namespace UsaRugbyStats\CompetitionApi\Extractor;

use UsaRugbyStats\Application\Common\ExtendedValidationGroupForm;
use Zend\Form\FormInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Location;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\Competition\Entity\Team;

class CompetitionMatchExtractor
{
    protected $form;

    public function __construct(ExtendedValidationGroupForm $f)
    {
        $this->form = $f;
    }

    public function extract(Match $m)
    {
        $this->form->bind($m);
        $this->form->isValid();

        $rawData = $this->form->getData(FormInterface::VALUES_AS_ARRAY);

        $rawData['_embedded'] = array();

        // Expand Competition entity
        if ( $m->getCompetition() instanceof Competition ) {
            $rawData['_embedded']['competition'] = [
                'id' => $m->getCompetition()->getId(),
                'name' => $m->getCompetition()->getName(),
                'type' => $m->getCompetition()->getType(),
                'typeName' => $m->getCompetition()->getTypeString(),
                'variant' => $m->getCompetition()->getVariant(),
                'maxPlayersOnRoster' => $m->getCompetition()->getMaxPlayersOnRoster(),
            ];
        }

        // Expand location entity
        if ( $m->getLocation() instanceof Location ) {
            $rawData['_embedded']['location'] = [
                'id' => $m->getLocation()->getId(),
                'name' => $m->getLocation()->getName(),
                'coordinates' => $m->getLocation()->getCoordinates(),
                'address' => $m->getLocation()->getAddress(),
            ];
        }

        // Expand team entities
        foreach ( $m->getTeams() as $team ) {
            if ( ! $team instanceof MatchTeam || ! $team->getTeam() instanceof Team ) {
                continue;
            }
            $rawData['_embedded']['team'][$team->getTeam()->getId()] = [
                'id' => $team->getTeam()->getId(),
                'name' => $team->getTeam()->getName(),
            ];

            if ( !empty($team->getPlayers()) ) {
                foreach ( $team->getPlayers() as $p ) {
                    if ( ! $p instanceof MatchTeamPlayer || ! $p->getPlayer() instanceof AccountInterface ) {
                        continue;
                    }
                    $rawData['_embedded']['player'][$p->getPlayer()->getId()] = [
                        'id' => $p->getPlayer()->getId(),
                        'name' => $p->getPlayer()->getDisplayName(),
                    ];
                }
            }
        }

        return $rawData;
    }
}