<?php
namespace UsaRugbyStats\Competition\Service;

use UsaRugbyStats\Application\Service\AbstractService;
use Doctrine\Common\Collections\Criteria;
use DoctrineModule\Paginator\Adapter\Selectable;
use Zend\Paginator\Paginator;

class TeamService extends AbstractService
{
    public function fetchAll(Criteria $c = null)
    {
        if ( is_null($c) ) {
            $c = new Criteria();
        }
        $orderings = $c->getOrderings();
        if ( empty($orderings) ) {
            $c->orderBy(['name' => 'ASC']);
        }

        return parent::fetchAll($c);
    }

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
            $c->expr()->contains('name', $q),
            $c->expr()->contains('remoteId', $q),
            $c->expr()->contains('email', $q)
        ));

        $adapter = new Selectable($this->getRepository(), $c);
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    public function findByRemoteID($id)
    {
        if ( empty($id) ) {
            throw new \InvalidArgumentException('You did not specify a valid identifier!');
        }

        return $this->getRepository()->findOneBy(['remoteId' => $id]);
    }
}
