<?php
namespace UsaRugbyStats\Application\Service;

use Zend\EventManager\EventInterface;

interface ServiceExtensionInterface
{
    public function checkPrecondition(EventInterface $e);
    public function execute(EventInterface $e);
}
