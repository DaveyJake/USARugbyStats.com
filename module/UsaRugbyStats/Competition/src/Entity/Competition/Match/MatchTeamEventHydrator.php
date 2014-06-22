<?php
namespace UsaRugbyStats\Competition\Entity\Competition\Match;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class MatchTeamEventHydrator extends DoctrineObject
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
        $result['event'] = $object->getDiscriminator();

        return $result;
    }
}
