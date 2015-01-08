<?php
namespace UsaRugbyStats\AccountProfile\Helper;

use LdcUserProfile\Form\PrototypeForm;
use ZfcRbac\Service\AuthorizationServiceInterface;
use Zend\Form\ElementInterface;
use Zend\Form\FieldsetInterface;
use UsaRugbyStats\Account\Entity\Rbac\AccountRbacInterface;

class PlayerProfileRbacHelper
{
    protected $authService;

    public function __construct(AuthorizationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function processForm(PrototypeForm $form, AccountRbacInterface $player)
    {
        if ( ! $player instanceof AccountRbacInterface || empty($player->getId()) ) {
            throw new \RuntimeException('Player profile form has not been populated!');
        }

        $context = ['player' => $player];

        $validationGroup = [];
        foreach ( $form->getFieldsets() as $fs ) {
            $validationGroup[$fs->getName()] = $this->processFormElement($fs, $context);
        }

        return $validationGroup;
    }

    protected function processFormElement(ElementInterface $e, array $context, $namePrefix = '')
    {
        if ($e instanceof FieldsetInterface) {
            $structure = [];
            foreach ( $e->getFieldsets() as $fs ) {
                $structure[$fs->getName()] = $this->processFormElement($fs, $context, $this->makeFieldNameString($namePrefix, $e->getName()));
            }
            foreach ( $e->getElements() as $child ) {
                if ( $this->processFormElement($child, $context, $this->makeFieldNameString($namePrefix, $e->getName())) ) {
                    $structure[] = $child->getName();
                }
            }

            return $structure;
        }

        $rbacPermission = 'account.profile.' . $this->makeFieldNameString($namePrefix, $e->getName());

        return $this->authService->isGranted($rbacPermission, $context + ['element' => $e->getName()]);
    }

    protected function makeFieldNameString($prefix, $name)
    {
        return $prefix . (empty($prefix) ? '' : '.') . $name;
    }
}
