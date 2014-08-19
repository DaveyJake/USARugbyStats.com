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

            // Process the role assignments
            isset($acct['membership']) && $this->processRoleAssignment($acct, 'member', 'memberships', $acct['membership']);
            isset($acct['comp_admin']) && $this->processRoleAssignment($acct, 'competition_admin','managedCompetitions', $acct['comp_admin']);
            isset($acct['team_admin']) && $this->processRoleAssignment($acct, 'team_admin','managedTeams', $acct['team_admin']);
            isset($acct['union_admin']) && $this->processRoleAssignment($acct, 'union_admin','managedUnions', $acct['union_admin']);

            $form = $this->serviceLocator->get('zfcuseradmin_createuser_form');

            // If there is no id element on the form, inject one
            if ( ! $form->has('id') ) {
                $form->add(array('name' => 'id', 'type' => 'Hidden'));
            }

            $entity = $svc->create($form, $acct);
            if (! $entity instanceof Account) {
                $this->getLogger()->crit(sprintf(
                    "ERROR: Failed to create account: %s (Message: %s)",
                    $acct['username'],
                    var_export($form->getMessages(), true)
                ));
                continue;
            }
            unset($form, $entity);
        }
    }

    protected function processRoleAssignment(&$acct, $role_name, $managedKey, $role_data)
    {
        if ( empty($role_data) ) {
            return;
        }
        if ( !isset($acct['roleAssignments']) || ! is_array($acct['roleAssignments']) ) {
            $acct['roleAssignments'] = array();
        }

        $role_data_parts = explode(',', $role_data);
        if ( count($role_data_parts) == 0 ) {
            return;
        }

        $roleAssignmentKey = null;
        foreach ($acct['roleAssignments'] as $raKey => $raData) {
            if ( isset($raData['type']) && $raData['type'] === $role_name ) {
                $roleAssignmentKey = $raData;
                if ( ! isset($acct['roleAssignments'][$raKey][$managedKey]) ) {
                    $acct['roleAssignments'][$raKey][$managedKey] = array();
                }
                break;
            }
        }
        if ( is_null($roleAssignmentKey) ) {
            $roleAssignmentKey = array_push($acct['roleAssignments'], [
                'type' => $role_name,
                $managedKey => []
            ]) - 1;
        }

        $digitFilter = new \Zend\Filter\Digits();
        foreach ($role_data_parts as $rdp) {
            $rdp = $digitFilter->filter($rdp);
            if ( empty($rdp) ) {
                continue;
            }
            array_push($acct['roleAssignments'][$roleAssignmentKey][$managedKey], $rdp);
        }
    }
}
