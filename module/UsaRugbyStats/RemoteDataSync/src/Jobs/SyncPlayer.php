<?php
namespace UsaRugbyStats\RemoteDataSync\Jobs;

use UsaRugbyStats\Competition\Service\TeamService;
use ZfcUserAdmin\Service\User as UserService;
use Zend\Form\FormInterface;
use UsaRugbyStats\Account\Entity\Account;
use Zend\Math\Rand;
use Zend\Stdlib\ArrayUtils;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member;
use UsaRugbyStats\Competition\Entity\Team\Member as TeamMembership;
use UsaRugbyStats\Account\Entity\Rbac\Role;

class SyncPlayer extends AbstractJob
{
    /**
     * @var TeamService
     */
    protected $svcTeam;

    /**
     * @var UserService
     */
    protected $svcUser;

    /**
     * @var FormInterface
     */
    protected $createUserForm;

    /**
     * @var FormInterface
     */
    protected $updateUserForm;

    /**
     * Default Payload Values
     *
     * @var array
     */
    protected $payloadDefaults = [
        'player_id'         => NULL,
        'player_data'       => NULL,
    ];

    public function run()
    {
        if ($this->args == $this->payloadDefaults) {
            throw new \InvalidArgumentException('Empty payload!');
        }

        // Find the local user account we're working with

        $this->getLogger()->debug('Locating local user account...');

        $player = $this->loadReferencedUserAccount();
        if (! $player instanceof Account) {
            $this->getLogger()->err('No local user account could be located or created!');
            throw new \RuntimeException('No local user account could be located or created!');
        }

        // Now that we have our user, time to do stuff!

        $this->getLogger()->debug('Updating Club Membership status...');
        $this->updateClubMembershipStatus($player, $this->args['player_data']);

        // If the user is new we skip the profile update as the data was imported on create
        if ( $player->getId() != NULL ) {
            $this->getLogger()->debug('Synchronizing profile data...');
            $this->updateAccountProfile($player, $this->args['player_data']);
        }

        $this->getLogger()->info('Completed!');
    }

    public function updateClubMembershipStatus($player, $data)
    {
        if ( !isset($data['club_ID']) || empty($data['club_ID']) ) {
            $this->getLogger()->debug(' ** No club_ID field provided');

            return NULL;
        }

        $team = $this->getTeamService()->findByRemoteId($data['club_ID']);
        if (! $team instanceof Team) {
            $this->getLogger()->debug(' ** No local club record matching specified club_ID');

            return NULL;
        }

        $membershipRole = $player->getRoleAssignment('member');
        if (! $membershipRole instanceof Member) {
            $membershipRole = new Member();
            $membershipRole->setRole(new Role($membershipRole->getDiscriminator()));
            $player->addRoleAssignment($membershipRole);
        }
        $teamMembership = $membershipRole->getMembershipForTeam($team);
        if (! $teamMembership instanceof TeamMembership) {
            $teamMembership = new TeamMembership();
        }
        $teamMembership->setTeam($team);
        $teamMembership->setMembershipStatus($data['Membership_Status']);
        $teamMembershipId = $teamMembership->getId();
        if ( empty($teamMembershipId) ) {
            $membershipRole->addMembership($teamMembership);
        }
    }

    public function updateAccountProfile($player, $data)
    {
        $mbrdata = $this->generateZfcUserAdminArrayFromPayload($data);
        unset($mbrdata['username']); // Don't overwrite their username

        // Bind the Player object to the form...
        $form = $this->getUserUpdateForm();
        $form->bind($player);
        $form->isValid();

        // And pull out an array we can use to persist
        $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
        // @HACK the ObjectSelect elements within the RoleAssignment objects don't get extracted for some reason
        array_walk_recursive($data, function (&$item, $key) {
            if ( is_object($item) && is_callable(array($item, 'getId')) ) {
                $item = $item->getId();
            }
        });

        // Merge in the basic account data
        $data = ArrayUtils::merge($data, $mbrdata);

        $player = $this->getUserService()->edit($form, $data, $player);
        if (! $player instanceof Account) {
            $this->getLogger()->err(' ** FAILED: ' . var_export($form->getMessages(), true));
            throw new \RuntimeException('Failed to update local player account!');
        }
    }

    public function loadReferencedUserAccount()
    {
        // If a player_id was specified, load it
        if ( ! empty($this->args['player_id']) ) {
            $this->getLogger()->debug('Loading Player with ID # ' . $this->args['player_id']);
            $player = $this->getUserService()->getUserMapper()->findById($this->args['player_id']);
            if ($player instanceof Account) {
                if ( isset($this->args['player_data']['ID']) ) {
                    // Update the user's RemoteID so we don't have to go though this again...
                    $player->setRemoteId($this->args['player_data']['ID']);
                    $this->getUserService()->getUserMapper()->update($player);
                }

                return $player;
            }
        }

        // Attempt to load by remote ID, if provided
        if ( isset($this->args['player_data']['ID']) ) {
            $player = $this->getUserService()->getUserMapper()->findByRemoteId($this->args['player_data']['ID']);
            if ($player instanceof Account) {
                return $player;
            }
        }

        // Attempt to load by email address, if provided
        if ( isset($this->args['player_data']['Email']) ) {
            $player = $this->getUserService()->getUserMapper()->findByEmail($this->args['player_data']['Email']);
            if ($player instanceof Account) {
                if ( isset($this->args['player_data']['ID']) ) {
                    // Update the user's RemoteID so we don't have to go though this again...
                    $player->setRemoteId($this->args['player_data']['ID']);
                    $this->getUserService()->getUserMapper()->update($player);
                }

                return $player;
            }
        }

        // Give them a new account
        if ( isset($this->args['player_data']) && !empty($this->args['player_data']) ) {
            return $this->createNewUserAccount($this->args['player_data']);
        }
    }

    public function createNewUserAccount($data)
    {
        $password = Rand::getString(128);

        $this->getLogger()->debug('Creating new account for ' . ($data['First_Name'] . ' ' . $data['Last_Name']));

        $form = $this->getUserCreateForm();
        $player = $this->getUserService()->create($form, ArrayUtils::merge(
            $this->generateZfcUserAdminArrayFromPayload($data),
            [ 'password' => $password, 'passwordVerify'  => $password ]
        ));
        if (! $player instanceof Account) {
            $this->getLogger()->err(' ** FAILED: ' . var_export($form->getMessages(), true));
            throw new \RuntimeException('Failed to create local player account!');
        }

        return $player;
    }

    protected function generateZfcUserAdminArrayFromPayload($data)
    {
        return [
            'remote_id'       => $data['ID'],
            'display_name'    => $data['Last_Name'] . ', ' . $data['First_Name'],
            'email'           => $data['Email'],
            // @TODO may be a good idea to ask for a unique username to be sent as well?
            'username'        => strtolower(preg_replace('{[^a-z0-9-]}is','',($data['First_Name'] . '' . $data['Last_Name']))),
            'password'        => '',
            'passwordVerify'  => '',
            'reset_password'  => '0',
        ];
    }

    /**
     * @return TeamService
     */
    public function getTeamService()
    {
        if ( empty($this->svcTeam) ) {
            $this->svcTeam = $this->getServiceLocator()->get(
                'usarugbystats_competition_team_service'
            );
        }

        return $this->svcTeam;
    }

    /**
     *
     * @param  TeamService $obj
     * @return self
     */
    public function setTeamService(TeamService $obj)
    {
        $this->svcTeam = $obj;

        return $this;
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        if ( is_null($this->svcUser) ) {
            $this->svcUser = $this->getServiceLocator()->get(
                'UsaRugbyStats\AccountAdmin\Service\UserService'
            );
        }

        return $this->svcUser;
    }

    /**
     *
     * @param  UserService $obj
     * @return self
     */
    public function setUserService(UserService $obj)
    {
        $this->svcUser = $obj;

        return $this;
    }

    /**
     * @return FormInterface
     */
    public function getUserCreateForm()
    {
        if ( empty($this->createUserForm) ) {
            $this->createUserForm = $this->getServiceLocator()->get(
                'zfcuseradmin_createuser_form'
            );
        }

        return $this->createUserForm;
    }

    /**
     * @param  FormInterface $form
     * @return self
     */
    public function setUserCreateForm($form)
    {
        $this->createUserForm = $form;

        return $this;
    }

    /**
     * @return FormInterface
     */
    public function getUserUpdateForm()
    {
        if ( empty($this->updateUserForm) ) {
            $this->updateUserForm = $this->getServiceLocator()->get(
                'zfcuseradmin_edituser_form'
            );
        }

        return $this->updateUserForm;
    }

    /**
     * @param  FormInterface $form
     * @return self
     */
    public function setUserUpdateForm($form)
    {
        $this->updateUserForm = $form;

        return $this;
    }

}
