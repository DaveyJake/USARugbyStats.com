<?php
namespace UsaRugbyStats\RemoteDataSync\DataProvider;

use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Account\Entity\Account;

interface DataProviderInterface
{
    public function syncTeam(Team $t);
    public function syncPlayer(Account $u);
}
