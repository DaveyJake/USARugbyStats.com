<?php
namespace UsaRugbyStats\Application\Common;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\Common\Persistence\ObjectRepository;

class ElementCollectionHydrator implements HydratorInterface
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    public function __construct(ObjectRepository $or)
    {
        $this->repository = $or;
    }

    public function extract($object)
    {
        return $object->getId();
    }

    public function hydrate(array $data, $object)
    {
        $object = $this->repository->find($data);

        return $object;
    }

}
