<?php

namespace Syhol\Fn;

class FnContainer
{
    protected static $global;

    protected $factory;

    protected $notFoundResolver;

    protected $items = [];

    public function __construct(callable $notFoundResolver = null)
    {
        $defaultResolver = function(){ throw new Exception('Function not found'); };
        $this->notFoundResolver = $notFoundResolver ? : $defaultResolver ;
    }

    public function set($key, callable $fn)
    {
        $this->items[$key] = $fn;

        return $this;
    }

    public function setNotFoundResolver(callable $notFoundResolver)
    {
        $this->notFoundResolver = $notFoundResolver;

        return $this;
    }

    public function get($key)
    {
        return isset($this->items[$key]) ? $this->items[$key] ? $this->notFoundResolver($key) ;
    }
}
