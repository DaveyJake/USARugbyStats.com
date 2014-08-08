<?php
namespace UsaRugbyStats\DataImporter\Service;

class FixtureService
{
    protected $fixtureMap = array();

    /**
     * @param ConfigInterface $config
     */
    public function __construct(array $fixtureMap)
    {
        $this->setFixtureMap($fixtureMap);
    }

    public function setFixtureMap(array $map)
    {
        $this->fixtureMap = $map;

        return $this;
    }

    public function getFixtureGroup($gid)
    {
        return @$this->fixtureMap[$gid];
    }

    public function getFixture($fqn)
    {
        $parts = explode('.', $fqn);

        return @$this->fixtureMap[$parts[0]][$parts[1]];
    }
}
