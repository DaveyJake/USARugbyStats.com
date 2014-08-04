<?php
namespace UsaRugbyStats\Application\FeatureFlags;

class FeatureFlagService
{
    protected $flagSets;

    public function register($name, $set = null)
    {
        $this->flagSets[$name] = $set instanceof FeatureFlags ? $set : new FeatureFlags($set);

        return $this;
    }

    public function unregister($nameOrObject)
    {
        if (! $nameOrObject instanceof FeatureFlags) {
            unset($this->flagSets[$nameOrObject]);

            return $this;
        }

        foreach ($this->flagSets as $name => $obj) {
            if ($obj === $nameOrObject) {
                unset($this->flagSets[$nameOrObject]);

                return $this;
            }
        }

        return $this;
    }

    public function get($name)
    {
        return isset($this->flagSets[$name])
            ? $this->flagSets[$name]
            : null;
    }
}
