<?php

namespace Syhol\Fn;

use Exception;
use ReflectionFunctionAbstract;

class Fn
{
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
        return $passed;
    }

    public function partialLeft()
    {
        $paramSize = $this->reflection->getNumberOfParameters();

        foreach (func_get_args() as $arg) {
            for ($i = 0; $i < $paramSize; $i++) {
                if ( ! isset($this->arguments[$i]) ) {
                    $this->arguments[$i] = $arg;
                }
            }
        }
    }

    public function partialRight()
    {
        $paramSize = $this->reflection->getNumberOfParameters();

        foreach (func_get_args() as $arg) {
            for ($i = $paramSize - 1; $i >= 0 ; $i--) {
                if ( ! isset($this->arguments[$i]) ) {
                    $this->arguments[$i] = $arg;
                }
            }
        }
    }

    public function partialAt($index, $arg)
    {
        $this->arguments[$index] = $arg;
    }

    public function partialFor($name, $arg)
    {
        $params = array_flip($this->getParameterNames());

        if (isset($params[$name])) {
            $this->arguments[$params[$name]] = $arg;
        }
    }

    public function curryLeft()
    {

    }

    public function curryRight()
    {

    }

    public function curryStop()
    {

    }

    /**
     * @param $interface
     * @throws Exception
     */
    public function impelments($interface)
    {
        throw new Exception('Not implemented');
    }

    public function unbindArgs()
    {

    }

    public function getParameterNames()
    {
        $params = $this->reflection->getParameters();
        $getter = function ($param) { return $param->getName(); };
        return array_map($getter, $params);
    }
}
