<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Service\AuthorizationService;
use ZfcUser\Entity\UserInterface;
use UsaRugbyStats\Account\Entity\Rbac\AccountRbacInterface;
use UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedPlayerAssertion;

class ExtensionRbacAssertion implements AssertionInterface
{
    /**
     * @param  AuthorizationService $authorization
     * @return bool
     */
    public function assert(AuthorizationService $authorization, $context = null)
    {
        $user = $authorization->getIdentity();
        if (! $user instanceof AccountRbacInterface) {
            throw new \InvalidArgumentException('Authorization service must have an authorized admin user');
        }
        $player = $context['player'];
        if (! $player instanceof UserInterface) {
            throw new \InvalidArgumentException('Context must contain the player account entity');
        }

        if ( $user->getId() === $player->getId() ) {
            return true;
        }

        return (new EnforceManagedPlayerAssertion())->assert($authorization, $player);
    }
}
