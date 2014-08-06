<?php
namespace UsaRugbyStats\Application\Common;

use DoctrineModule\Form\Element\ObjectSelect as DoctrineObjectSelect;
use DoctrineModule\Form\Element\Proxy;

class ObjectSelect extends DoctrineObjectSelect
{
    public function resetProxy()
    {
        $proxy = new Proxy();
        $proxy->setDisplayEmptyItem($this->proxy->getEmptyItemLabel());
        $proxy->setEmptyItemLabel($this->proxy->getEmptyItemLabel());
        $proxy->setFindMethod($this->proxy->getFindMethod());
        $proxy->setIsMethod($this->proxy->getIsMethod());
        if ( $this->proxy->getLabelGenerator() ) {
            $proxy->setLabelGenerator($this->proxy->getLabelGenerator());
        }
        $proxy->setObjectManager($this->proxy->getObjectManager());
        $proxy->setProperty($this->proxy->getProperty());
        $proxy->setTargetClass($this->proxy->getTargetClass());

        $this->proxy = $proxy;
        $this->valueOptions = null;

        return $this;
    }

    public function setFindMethod($array)
    {
        $options = $this->getOptions();
        $options['find_method'] = $array;
        parent::setOptions($options);

        $this->resetProxy();

        return $this;
    }
}
