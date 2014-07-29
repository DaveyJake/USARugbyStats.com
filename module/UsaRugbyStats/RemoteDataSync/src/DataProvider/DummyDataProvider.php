<?php
namespace UsaRugbyStats\RemoteDataSync\DataProvider;

use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Account\Entity\Account;

class DummyDataProvider implements DataProviderInterface
{
    public $dummyData = array();

    public function syncTeam(Team $t)
    {
        if ( $t->getRemoteId() == NULL ) {
            return NULL;
        }

        $data = null;
        if ( isset($this->dummyData['sync_team'][$t->getId()]) ) {
            $data = $this->processWithFaker($this->dummyData['sync_team'][$t->getId()]);
        } elseif ( isset($this->dummyData['sync_team']['*']) ) {
            $data = $this->processWithFaker($this->dummyData['sync_team']['*']);
        }

        return $data;
    }

    public function syncPlayer(Account $u)
    {
        if ( $u->getRemoteId() == NULL ) {
            return NULL;
        }

        $data = null;
        if ( isset($this->dummyData['sync_player'][$u->getId()]) ) {
            $data = $this->processWithFaker($this->dummyData['sync_player'][$u->getId()]);
        } elseif ( isset($this->dummyData['sync_player']['*']) ) {
            $data = $this->processWithFaker($this->dummyData['sync_player']['*']);
        }
        if ( !empty($data) && $u->getRemoteId() ) {
            $data['ID'] = $u->getRemoteId();
        }

        return $data;
    }

    protected function processWithFaker($data)
    {
        if (empty($data)) {
            return $data;
        }

        $faker = \Faker\Factory::create();
        array_walk_recursive($data, function (&$item) use ($faker) {
            if ( ! preg_match_all('{{{faker::([^\}]+)}}}is', $item, $matches) ) {
                return;
            }
            foreach ($matches[0] as $key=>$match) {
                $item = str_replace($match, $faker->{$matches[1][$key]}, $item);
            }
        });

        return $data;
    }
}
