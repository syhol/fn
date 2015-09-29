<?php

namespace Syhol\Fn;


class FnContainer
{
    protected static $global;

    protected $factory;

    protected $notFoundResolver;

    protected $items = [];

    public function __construct(FnFactory $factory)
    {
        $this->items['fnFactory'] = $factory->parse([$factory, 'parse']);
        $this->items['fnContainer'] = $factory->poly(
            [$this, 'set'],
            [$this, 'get'],
        );
        $this->notFoundResolver = function(){};
    }

    public function set($key, callable $fn)
    {
        $this->items[$key] = $this->factory->parse($fn);

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

    public function setGlobal(FnContainer $container)
    {
        return self::$global = $container;
    }

    public function getGlobal()
    {
        return self::$global;
    }
}
