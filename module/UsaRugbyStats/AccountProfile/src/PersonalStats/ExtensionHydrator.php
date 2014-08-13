<?php
namespace UsaRugbyStats\AccountProfile\PersonalStats;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class ExtensionHydrator extends DoctrineObject
{
    protected $defaults =[
        'height_ft' => NULL,
        'height_in' => NULL,
        'weight_lbs' => NULL,
        'weight_oz' => NULL,
        'benchPress' => NULL,
        'sprintTime' => NULL,
    ];

    public function extract($object)
    {
        $data = array_merge($this->defaults, parent::extract($object));

        if ( ! empty($data['height']) ) {
            $data['height_ft'] = floor($data['height'] / 12);
            $data['height_in'] = $data['height'] % 12;
        }
        unset($data['height']);

        if ( ! empty($data['weight']) ) {
            $data['weight_lbs'] = floor($data['weight'] / 16);
            $data['weight_oz'] = $data['weight'] % 16;
        }
        unset($data['weight']);

        // Clear out empty values so they don't mess up the form
        foreach ($data as $key=>$value) {
            if ( empty($value) ) {
                $data[$key] = null;
            }
        }

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
