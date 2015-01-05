<?php
namespace UsaRugbyStats\Competition\Service;

use UsaRugbyStats\Application\Service\AbstractService;
use Doctrine\Common\Collections\Criteria;
use DoctrineModule\Paginator\Adapter\Selectable;
use Zend\Paginator\Paginator;

class CompetitionService extends AbstractService
{
    public function findAllBySearchQuery($q, Criteria $c = null)
    {
        if ( is_null($c) ) {
            $c = new Criteria();
        }
        $orderings = $c->getOrderings();
        if ( empty($orderings) ) {
            $c->orderBy(['name' => 'ASC']);
        }

        $c->where($c->expr()->orX(
            $c->expr()->contains('name', $q)
        ));

        $adapter = new Selectable($this->getRepository(), $c);
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    public function findLeagueCompetitionForTeam($team)
    {
        return $this->getRepository()->findLeagueCompetitionForTeam($team);
    }

    public function findFriendlyCompetitions()
    {
        return $this->getRepository()->findFriendlyCompetitions();
    }

    public function create(array $data)
    {
        // If they've removed all the divisions make sure we send an empty array
        if ( ! isset($data['competition']['divisions']) || empty($data['competition']['divisions']) ) {
            $data['competition']['divisions'] = array();
        }

        return parent::create($data);
    }

    public function update($entity, array $data)
    {
        // If they've removed all the divisions make sure we send an empty array
        if ( ! isset($data['competition']['divisions']) || empty($data['competition']['divisions']) ) {
            $data['competition']['divisions'] = array();
        }

        return parent::update($entity, $data);
    }

}
