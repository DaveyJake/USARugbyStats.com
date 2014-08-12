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

        // Allow manually setting the record identifer
        $metadata = $this->serviceLocator->get('zfcuser_doctrine_em')->getClassMetadata('UsaRugbyStats\Account\Entity\Account');
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        foreach ($data as $acct) {
            $this->getLogger()->debug(" - {$acct['username']}");

            // Process the password field
            if ( !isset($acct['password']) || empty($acct['password']) ) {
                $acct['password'] = Rand::getString(24,null,true);
                $this->getLogger()->debug(" (password = " . $acct['password'] . " )");
            }
            $acct['passwordVerify'] = $acct['password'];

            // If no email address is provided, use a dummy one
            if ( !isset($acct['email']) || empty($acct['email']) ) {
                $acct['email'] = $acct['username'] . '@usarugbystats.com';
            }

            // If First and Last name are provided, update the display name
            if ( isset($acct['first_name']) && isset($acct['last_name']) ) {
                $acct['display_name'] = sprintf('%s, %s', $acct['last_name'], $acct['first_name']);
            }
            if ( !isset($acct['display_name']) || empty($acct['display_name']) ) {
                $acct['display_name'] = $acct['username'];
            }

            $form = $this->serviceLocator->get('zfcuseradmin_createuser_form');

            // If there is no id element on the form, inject one
            if ( ! $form->has('id') ) {
                $form->add(array('name' => 'id', 'type' => 'Hidden'));
            }

            $entity = $svc->create($form, $acct);
            if (! $entity instanceof Account) {
                $this->getLogger()->crit("ERROR: Failed to create account: " . $acct['username']);
                continue;
            }
            unset($form, $entity);
        }
    }
}
