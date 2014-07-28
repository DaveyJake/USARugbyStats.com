<?php
namespace UsaRugbyStats\Competition\Service;

use UsaRugbyStats\Application\Service\AbstractService;

class TeamService extends AbstractService
{
    public function findByRemoteID($id)
    {
        if ( empty($id) ) {
            throw new \InvalidArgumentException('You did not specify a valid identifier!');
        }

        return $this->getRepository()->findOneBy(['remoteId' => $id]);
    }
}
