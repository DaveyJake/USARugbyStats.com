<?php
namespace UsaRugbyStats\Account\DataImporter;

use UsaRugbyStats\DataImporter\Task\TaskInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use UsaRugbyStats\Account\Entity\Account;
use Zend\Math\Rand;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImportAccountsTask implements TaskInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $serviceLocator;

    public function __construct(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
    }

    public function execute(array $data)
    {
        $this->getLogger()->debug('Importing Account records...');

        $svc  = $this->serviceLocator->get('UsaRugbyStats\AccountAdmin\Service\UserService');

        foreach ($data as $acct) {
            $this->getLogger()->debug(" - {$acct['username']}");
            if ( !isset($acct['password']) || empty($acct['password']) ) {
                $acct['password'] = Rand::getString(24,null,true);
                $this->getLogger()->debug(" (password = " . $acct['password'] . " )");
            }
            $acct['passwordVerify'] = $acct['password'];

            $form = $this->serviceLocator->get('zfcuseradmin_createuser_form');
            $entity = $svc->create($form, $acct);
            if (! $entity instanceof Account) {
                $this->getLogger()->crit("ERROR: Failed to create account: " . $acct['username']);
                continue;
            }
            unset($form, $entity);
        }
    }
}
