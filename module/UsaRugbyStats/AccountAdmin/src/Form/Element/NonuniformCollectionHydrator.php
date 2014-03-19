<?php
namespace UsaRugbyStats\AccountAdmin\Form\Element;
 
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
 
class NonuniformCollectionHydrator extends DoctrineObject
{
    public function extract($object)
    {
        $data = parent::extract($object);
        $data['__class__'] = get_class($object);
        return $data;
    }
 
    public function hydrate(array $data, $object)
    {
        $obj = isset($data['__class__']) ? new $data['__class__'] : $object;
        unset($data['__class__']);
        return parent::hydrate($data, $obj);
    }
}