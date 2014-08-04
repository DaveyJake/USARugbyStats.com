<?php
namespace UsaRugbyStats\Competition\Service;

use UsaRugbyStats\Application\Service\AbstractService;
use Doctrine\Common\Collections\Criteria;

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

    public function findByRemoteID($id)
    {
        if ( empty($id) ) {
            throw new \InvalidArgumentException('You did not specify a valid identifier!');
        }

        return $this->getRepository()->findOneBy(['remoteId' => $id]);
    }
}
