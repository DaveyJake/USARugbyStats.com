<?php
namespace UsaRugbyStats\Competition\Hydrator;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class ObjectPopulateStrategy implements StrategyInterface
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function extract($value)
    {
        if ( empty($value) || is_scalar($value) ) {
            return $value;
        }

        return $value->getId();
    }

    public function hydrate($value)
    {
        if (empty($value)) {
            return null;
        }
        if ( ! is_scalar($value) ) {
            return $value;
        }

        return $this->repository->find($value);
    }

}
