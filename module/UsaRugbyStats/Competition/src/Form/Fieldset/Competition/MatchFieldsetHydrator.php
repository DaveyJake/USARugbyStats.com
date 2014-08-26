<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class MatchFieldsetHydrator extends DoctrineObject
{
    protected $dateFormat = 'Y-m-d';
    protected $timeFormat = 'g:i A';

    /* (non-PHPdoc)
     * @see \DoctrineModule\Stdlib\Hydrator\DoctrineObject::extract()
     */
    public function extract($object)
    {
        $data = parent::extract($object);
        $data['date_time'] = $data['date'] ? $data['date']->format($this->timeFormat) : NULL;
        $data['date_date'] = $data['date'] ? $data['date']->format($this->dateFormat) : NULL;
        unset($data['date']);

        return $data;
    }

    /* (non-PHPdoc)
     * @see \DoctrineModule\Stdlib\Hydrator\DoctrineObject::hydrate()
     */
    public function hydrate(array $data, $object)
    {
        $data['date'] = \DateTime::createFromFormat(
            $this->dateFormat . ' ' . $this->timeFormat . ' e',
            $data['date_date'] . ' ' . $data['date_time'] . ' ' . $data['timezone']
        );
        unset($data['date_date'], $data['date_time']);

        return parent::hydrate($data, $object);
    }

}
