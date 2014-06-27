<?php
namespace UsaRugbyStats\AccountProfile\PersonalStats;

use LdcUserProfile\Extensions\AbstractExtension;
use ZfcUser\Entity\UserInterface;
use UsaRugbyStats\Application\Service\AbstractService;

class Extension extends AbstractExtension
{
    protected $service;

    public function getName()
    {
        return 'personalstats';
    }

    public function getObjectForUser(UserInterface $user)
    {
        return $this->getService()->getRepository()->findOneByAccount($user->getId());
    }

    public function save($entity)
    {
        if ( !isset($entity->personalstats) || ! $entity->personalstats instanceof ExtensionEntity  ) {
            return false;
        }

        $entity->personalstats->setAccount($entity->zfcuser);
        $this->getService()->save($entity->personalstats);

        return $entity->personalstats->getId() !== NULL;
    }

    public function getService()
    {
        return $this->service;
    }

    public function setService(AbstractService $service)
    {
        $this->service = $service;

        return $this->service;
    }

}
