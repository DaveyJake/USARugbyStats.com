<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile;

use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Service\AuthorizationService;
use ZfcUser\Entity\UserInterface;
use UsaRugbyStats\Account\Entity\Rbac\AccountRbacInterface;
use UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedPlayerAssertion;

class ExtensionRbacAssertion implements AssertionInterface
{
    protected $enabledWhenRemoteIdPresent = ['photoSource', 'custom_photo'];

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

        // If this player is linked to the remote system, block editing of synced fields
        // @TODO should this be an event listener stored in RemoteDataSync ?
        if ($player->getRemoteId() > 0 && !in_array($context['element'], $this->enabledWhenRemoteIdPresent, true)) {
            return false;
        }

        if ( $user->getId() === $player->getId() ) {
            return true;
        }

        return (new EnforceManagedPlayerAssertion())->assert($authorization, $player);
    }
}
