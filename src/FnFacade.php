<?php

namespace Syhol\Fn;


class FnFacade
{
    protected static $global;

    protected $factory;

    protected $container;

    public function __construct(FnFactory $factory, FnContainer $container)
    {
        $this->factory = $factory;
        $this->container = $container;
        $container->set('fnFactory', $this->poly([$factory, 'parse']));
        $container->set('fnContainer', $this->poly([$this, 'set'], [$this, 'get']));
    }

    public function set($key, callable $fn)
    {
        $this->container->set($key, $this->factory->parse($fn));

        return $this;
    }

    public function get($key)
    {
        return $this->container->get($key);
    }

    public function parse(callable $callable)
    {
        return $this->factory->parse($callable);
    }
    
    public function poly(array $items)
    {
        return $this->factory->poly($items);
    }

    public static function setGlobal(FnContainer $container)
    {
        return self::$global = $container;
    }

    public static function getGlobal()
    {
        return self::$global;
    }
}
