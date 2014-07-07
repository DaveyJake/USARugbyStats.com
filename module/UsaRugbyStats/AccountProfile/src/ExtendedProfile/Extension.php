<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

use LdcUserProfile\Extensions\AbstractExtension;
use ZfcUser\Entity\UserInterface;
use UsaRugbyStats\Application\Service\AbstractService;

class Extension extends AbstractExtension
{
    protected $service;

    public function getName()
    {
        return 'extprofile';
    }

    public function getObjectForUser(UserInterface $user)
    {
        return $this->getService()->getRepository()->findOneByAccount($user->getId());
    }

    public function save($entity)
    {
        if ( !isset($entity->extprofile) || ! $entity->extprofile instanceof ExtensionEntity  ) {
            return false;
        }

        $entity->extprofile->setAccount($entity->zfcuser);

        // Update display name to use the provided first and last names
        $entity->zfcuser->setDisplayName(implode(', ', [
            $entity->extprofile->getLastName(),
            $entity->extprofile->getFirstName(),
        ]));

        $this->getService()->save($entity->extprofile);

        return $entity->extprofile->getId() !== NULL;
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
