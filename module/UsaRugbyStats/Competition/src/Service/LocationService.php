<?php
namespace UsaRugbyStats\Competition\Service;

use UsaRugbyStats\Application\Service\AbstractService;
use Doctrine\Common\Collections\Criteria;
use DoctrineModule\Paginator\Adapter\Selectable;
use Zend\Paginator\Paginator;

class LocationService extends AbstractService
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
            $c->expr()->contains('address', $q),
            $c->expr()->contains('coordinates', $q)
        ));

        $adapter = new Selectable($this->getRepository(), $c);
        $paginator = new Paginator($adapter);

        return $paginator;
    }
}
