<?php
namespace UsaRugbyStats\Competition\Service;

use UsaRugbyStats\Application\Service\AbstractService;
use Doctrine\Common\Collections\Criteria;
use DoctrineModule\Paginator\Adapter\Selectable;
use Zend\Paginator\Paginator;

class UnionService extends AbstractService
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
}
