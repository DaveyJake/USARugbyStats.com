<?php
namespace UsaRugbyStats\Account\Entity\Rbac;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class RoleAssignmentHydrator extends DoctrineObject
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        $result = parent::extract($object);
        $result['type'] = $object->getDiscriminator();

        return $result;
    }
}
