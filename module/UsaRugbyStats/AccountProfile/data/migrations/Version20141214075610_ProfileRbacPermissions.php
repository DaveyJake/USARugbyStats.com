<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141214075610_ProfileRbacPermissions extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $permissions = <<<EOB
INSERT IGNORE INTO `accounts_rbac_permission` (`id`, `name`) VALUES
(33, 'account.profile'),
(34, 'account.profile.zfcuser.username'),
(35, 'account.profile.zfcuser.email'),
(36, 'account.profile.zfcuser.display_name'),
(37, 'account.profile.zfcuser.password'),
(38, 'account.profile.zfcuser.passwordVerify'),
(39, 'account.profile.extprofile.firstName'),
(40, 'account.profile.extprofile.lastName'),
(41, 'account.profile.extprofile.telephoneNumber'),
(42, 'account.profile.extprofile.photoSource'),
(43, 'account.profile.extprofile.custom_photo'),
(44, 'account.profile.personalstats.height_ft'),
(45, 'account.profile.personalstats.height_in'),
(46, 'account.profile.personalstats.weight_lbs'),
(47, 'account.profile.personalstats.weight_oz'),
(48, 'account.profile.personalstats.benchPress'),
(49, 'account.profile.personalstats.sprintTime');
EOB;
        $this->addSql($permissions);

        $assignments = <<<EOB
INSERT IGNORE INTO `accounts_rbac_roles_permissions` (`role_id`, `permission_id`) VALUES
(2, 33),
(6, 34),
(2, 35),
(6, 36),
(2, 37),
(2, 38),
(2, 39),
(2, 40),
(2, 41),
(2, 42),
(2, 43),
(2, 44),
(2, 45),
(2, 46),
(2, 47),
(2, 48),
(2, 49);
EOB;
        $this->addSql($assignments);

    }

    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `accounts_rbac_roles_permissions` WHERE permission_id >= 33 AND permission_id <= 49;");
        $this->addSql("DELETE FROM `accounts_rbac_permission` WHERE id >= 33 AND id <= 49");
    }
}
