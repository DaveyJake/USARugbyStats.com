<?php
namespace UsaRugbyStats\Application\FeatureFlags;

class FeatureFlag
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }

    public function set($value)
    {
        $this->value = $value;

        return $this;
    }

    public function on()
    {
        $this->set(true);

        return $this;
    }

    public function off()
    {
        $this->set(false);

        return $this;
    }

    public function andWith($value)
    {
        $this->set(is_null($this->value)
            ? $value
            : $this->value && $value
        );

        return $this;
    }

    public function orWith($value)
    {
        $this->set(is_null($this->value)
            ? $value
            : $this->value || $value
        );

        return $this;
    }

    public function is_set()
    {
        return $this->value !== NULL;
    }

    public function is_unset()
    {
        return $this->value === NULL;
    }

    public function is_on()
    {
        return $this->value === true;
    }

    public function is_off()
    {
        return $this->value === false;
    }

    public function is_eq($val)
    {
        return $this->value == $val;
    }

    public function is($val)
    {
        return $this->value === $val;
    }

    public function is_in($haystack, $strict=true)
    {
        return in_array($this->value, $haystack, $strict);
    }

    public function has($val, $strict=true)
    {
        if ( empty($this->value) ) {
            return false;
        }
        if ( ! is_array($this->value) ) {
            throw new \DomainException('has() can only be used on array flags!');
        }

        return in_array($val, $this->value, $strict);
    }

    public function push()
    {
        if ( empty($this->value) ) {
            $this->value = array();
        }
        if ( !empty($this->value) && ! is_array($this->value) ) {
            throw new \DomainException('push() can only be used on array flags!');
        }

        foreach ( func_get_args() as $item ) {
            array_push($this->value, $item);
        }

        return $this;
    }

    public function pop()
    {
        if ( empty($this->value) ) {
            return NULL;
        }
        if ( ! is_array($this->value) ) {
            throw new \DomainException('pop() can only be used on array flags!');
        }

        return array_pop($this->value);
    }

    public function unshift()
    {
        if ( empty($this->value) ) {
            $this->value = array();
        }
        if ( !empty($this->value) && ! is_array($this->value) ) {
            throw new \DomainException('unshift() can only be used on array flags!');
        }

        foreach ( func_get_args() as $item ) {
            array_unshift($this->value, $item);
        }

        return $this;
    }

    public function shift()
    {
        if ( empty($this->value) ) {
            return NULL;
        }
        if ( ! is_array($this->value) ) {
            throw new \DomainException('pop() can only be used on array flags!');
        }

        return array_shift($this->value);
    }

    public function __toString()
    {
        return $this->get();
    }
}
