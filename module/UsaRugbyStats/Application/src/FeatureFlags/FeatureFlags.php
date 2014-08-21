<?php
namespace UsaRugbyStats\Application\FeatureFlags;

class FeatureFlags
{
    protected $flags = array();

    public function __construct($flags = array())
    {
        $this->flags = array_filter($flags, function ($item) {
            return $item instanceof FeatureFlag;
        });
    }

    public function has($key)
    {
        return isset($this->flags[$key]);
    }

    public function on($key)
    {
        return $this->has($key) && $this->flags[$key]->on();
    }

    public function off($key)
    {
        return $this->has($key) && $this->flags[$key]->val() === false;
    }

    public function __get($key)
    {
        return $this->has($key)
            ? $this->flags[$key]
            : ($this->flags[$key] = new FeatureFlag(null) );
    }

    public function __set($key,$value)
    {
        $this->flags[$key] = $value instanceof FeatureFlag
            ? $value
            : new FeatureFlag($value);

        return $this;
    }

    public function toArray()
    {
        $copy = $this->flags;
        foreach ( $copy as $key => &$value ) {
            if ( $value instanceof FeatureFlag ) {
                $copy[$key] = $value->toArray();
            }
        }
        return $copy;
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
