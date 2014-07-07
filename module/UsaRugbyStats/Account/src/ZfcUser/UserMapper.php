<?php
namespace UsaRugbyStats\Account\ZfcUser;

use ZfcUserDoctrineORM\Mapper\User as ZfcUserDoctrineORMMapper;
use Zend\Stdlib\Hydrator\HydratorInterface;

class UserMapper extends ZfcUserDoctrineORMMapper
{
    public function findAll()
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('repository' => $er));
        $resultset = $er->findAll();
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('resultset' => &$resultset));

        return $resultset;
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('entity' => $entity));
        $result = parent::insert($entity, $tableName, $hydrator);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('entity' => $entity));

        return $result;
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('entity' => $entity));
        $result = parent::update($entity, $tableName, $hydrator);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('entity' => $entity));

        return $result;
    }

    public function remove($entity)
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('entity' => $entity));
        $this->em->remove($entity);
        $this->em->flush();
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('entity' => $entity));
    }

}
