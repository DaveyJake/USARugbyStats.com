<?php
namespace UsaRugbyStats\AccountAdmin\Entity;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class AccountHydrator extends DoctrineObject
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
        $result = $this->mapField('id', 'userId', $result);
        $result = $this->mapField('displayName', 'display_name', $result);

        return $result;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array  $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        //@TODO there is a discrepancy with ZfcUser or ZfcUserAdmin
        //      extract does id->userId, but hydrate leaves it as userId ???
        //$data = $this->mapField('userId', 'id', $data);
        $data = $this->mapField('display_name', 'displayName', $data);

        return parent::hydrate($data, $object);
    }

    protected function mapField($keyFrom, $keyTo, array $array)
    {
        $array[$keyTo] = $array[$keyFrom];
        unset($array[$keyFrom]);

        return $array;
    }
}
