<?php

namespace Syhol\Fn;

use Exception;
use ReflectionFunctionAbstract;

class Fn
{
    const NOVAL = 'FN_LIB_NO_VALUE';

    protected $arguments = [];

    protected $reflection;

    protected $callable;

    public function __construct(callable $callable, ReflectionFunctionAbstract $reflection)
    {
        $this->callable = $callable;
        $this->reflection = $reflection;
    }

    public function __invoke()
    {
        $arguments = $this->buildArguments(func_get_args());

        return call_user_func_array($this->callable, $arguments);
    }

    public function buildArguments($passed = [])
    {
        $args = $this->arguments;

        foreach ($passed as $key => $arg) {
            if (is_string($key)) {
                $args[$key] = $arg;
            } else {
                $i = $this->getNextLeftIndex();
                $args[$i] = $arg;
            }
        }

        return $passed;
    }

    public function hasArgument($key)
    {
        $key = is_string($key) ? $this->getParameterIndexFromName($key) : $key;

        return $key !== false && isset($this->arguments[$key]);
    }

    public function getArgument($key)
    {
        $key = is_string($key) ? $this->getParameterIndexFromName($key) : $key;

        return $key !== false ? $this->arguments[$key] : null;
    }

    public function partialLeft()
    {
        foreach (func_get_args() as $arg) {
            $i = $this->getNextLeftIndex();
            $this->arguments[$i] = $arg;
        }

        return $this;
    }

    public function partialRight()
    {
        foreach (func_get_args() as $arg) {
            $i = $this->getNextRightIndex();
            $this->arguments[$i] = $arg;
        }

        return $this;
    }

    public function partialAt($index, $arg = self::NOVAL)
    {
        if ($arg === self::NOVAL) {
            return function($arg) use ($index) {
                $this->partialAt($index, $arg);
            };
        }

        $this->arguments[$index] = $arg;

        return $this;
    }

    public function partialFor($name, $arg = self::NOVAL)
    {
        if ($arg === self::NOVAL) {
            return function($arg) use ($name) {
                $this->partialFor($name, $arg);
            };
        }

        $index = $this->getParameterIndexFromName($name);
        if ($index !== false) {
            $this->arguments[$index] = $arg;
        }

        return $this;
    }

//    public function curryLeft()
//    {
//
//    }
//
//    public function curryRight()
//    {
//
//    }
//
//    public function curryStop()
//    {
//
//    }
//
//    public function impelments($interface)
//    {
//        throw new Exception('Not implemented');
//    }
//
//    public function unbindArgs()
//    {
//
//    }

    public function getParameterNames()
    {
        $params = $this->reflection->getParameters();
        $getter = function ($param) { return $param->getName(); };
        return array_map($getter, $params);
    }

    private function getNextLeftIndex()
    {
        $i = 0;
        while (isset($this->arguments[$i])) {
            $i++;
        }
        return $i;
    }

    private function getNextRightIndex()
    {
        $paramSize = $this->reflection->getNumberOfParameters();

        for ($i = $paramSize - 1; $i >= 0 ; $i--) {
            if (!isset($this->arguments[$i])) {
                return $i;
            }
        }
        return $this->getNextLeftIndex();
    }

    /**
     * @param $key
     * @return bool|int
     */
    public function getParameterIndexFromName($key)
    {
        $names = array_flip($this->getParameterNames());
        return isset($names[$key]) ? $names[$key] : false;
    }

    /**
     * @param $key
     * @return bool|string
     */
    public function getParameterNameFromIndex($key)
    {
        $names = $this->getParameterNames();
        return isset($names[$key]) ? $names[$key] : false;
    }
}
