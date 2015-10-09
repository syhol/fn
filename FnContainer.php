<?php

namespace Syhol\Fn;


class FnContainer
{
    protected static $global;

    protected $factory;

    protected $notFoundResolver;

    protected $items = [];

    public function __construct(FnFactory $factory, callable $notFoundResolver = null)
    {
        $this->factory = $factory;
        $this->notFoundResolver = $notFoundResolver ? : function(){ throw new Exception('Function not found'); };
        $this->items['fnFactory'] = $this->parse([$factory, 'parse']);
        $this->items['fnContainer'] = $this->poly([$this, 'set'], [$this, 'get']);
    }

    public function set($key, callable $fn)
    {
        $this->items[$key] = $this->parse($fn);

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

    public function parse(callable $callable)
    {
        return $factory->parse($callable);
    }
    
    public function poly(array $items)
    {
        return $factory->poly($items);
    }

    public function setGlobal(FnContainer $container)
    {
        return self::$global = $container;
    }

    public function getGlobal()
    {
        return self::$global;
    }
}
