<?php
namespace UsaRugbyStats\AccountProfile\PersonalStats;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class ExtensionHydrator extends DoctrineObject
{

    public function extract($object)
    {
        $data = parent::extract($object);

        $data['height_ft'] = floor($data['height'] / 12);
        $data['height_in'] = $data['height'] % 12;
        unset($data['height']);

        $data['weight_lbs'] = floor($data['weight'] / 16);
        $data['weight_oz'] = $data['weight'] % 16;
        unset($data['weight']);

        return $data;
    }

    public function hydrate(array $data, $object)
    {
        $data['height'] = $data['height_ft'] * 12 + $data['height_in'];
        unset($data['height_ft'], $data['height_in']);

        $data['weight'] = $data['weight_lbs'] * 16 + $data['weight_oz'];
        unset($data['weight_lbs'], $data['weight_oz']);

        return parent::hydrate($data, $object);
    }

}
