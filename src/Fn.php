<?php

namespace Syhol\Fn;

use ArrayAccess;
use ReflectionFunctionAbstract;

class Fn implements ArrayAccess
{
    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var ReflectionFunctionAbstract
     */
    protected $reflection;

    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param callable $callable
     * @param ReflectionFunctionAbstract $reflection
     * @param array $arguments
     */
    public function __construct(
        callable $callable,
        ReflectionFunctionAbstract $reflection,
        $arguments = []
    ) {
        $this->callable = $callable;
        $this->reflection = $reflection;
        $this->arguments = $arguments;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        $arguments = $this->buildArguments(func_get_args());

        return call_user_func_array($this->callable, $arguments);
    }

    /**
     * @param array $passed
     * @return array
     */
    public function buildArguments($passed = [])
    {
        $args = $this->arguments;

        foreach ($passed as $key => $arg) {
            $key = is_string($key) ? $this->getParameterIndexFromName($key) : $this->getNextLeftIndex();
            $args[$key] = $arg;
        }

        return $args;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasArgument($key)
    {
        $key = is_string($key) ? $this->getParameterIndexFromName($key) : $key;

        return $key !== false && isset($this->arguments[$key]);
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function getArgument($key, $default = null)
    {
        $key = is_string($key) ? $this->getParameterIndexFromName($key) : $key;

        return $key !== false ? $this->arguments[$key] : $default;
    }

    /**
     * @param $arg
     * @return Fn
     */
    public function partialLeft($arg)
    {
        return $this->partialAt($this->getNextLeftIndex(), $arg);
    }

    /**
     * @param $arg
     * @return Fn
     */
    public function partialRight($arg)
    {
        return $this->partialAt($this->getNextRightIndex(), $arg);
    }

    /**
     * @param $name
     * @param $arg
     * @return Fn
     */
    public function partialFor($name, $arg)
    {
        $index = $this->getParameterIndexFromName($name);

        return $index === false ? $this : $this->partialAt($index, $arg);
    }

    /**
     * @param $index
     * @param $arg
     * @return static
     */
    public function partialAt($index, $arg)
    {
        $args = $this->arguments;

        $args[$index] = $arg;

        return new static($this->callable, $this->reflection, $args);
    }

    public function implementsInterface(ReflectionFunctionAbstract $interface)
    {
        return (new ImplementationChecker())->checkFunctions($this->reflection, $interface);
    }

    /**
     * @return static
     */
    public function unbound()
    {
        return new static($this->callable, $this->reflection, []);
    }

    /**
     * @return ReflectionFunctionAbstract
     */
    public function reflect()
    {
        return $this->reflection;
    }

    /**
     * @return array
     */
    public function getParameterNames()
    {
        $params = $this->reflection->getParameters();
        $getter = function ($param) {
            return $param->getName();
        };

        return array_map($getter, $params);
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

    /**
     * @return int
     */
    private function getNextLeftIndex()
    {
        $i = 0;
        while (isset($this->arguments[$i])) {
            $i++;
        }

        return $i;
    }

    /**
     * @return int
     */
    private function getNextRightIndex()
    {
        $paramSize = $this->reflection->getNumberOfParameters();

        for ($i = $paramSize - 1; $i >= 0; $i--) {
            if (!isset($this->arguments[$i])) {
                return $i;
            }
        }

        return $this->getNextLeftIndex();
    }

    /**
     * Whether an argument exists
     *
     * @param mixed $key
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($key)
    {
        $key = is_string($key) ? $this->getParameterIndexFromName($key) : $key;
        $key = $key === false ? -1 : $key ;
        return isset($this->arguments[$key]);
    }

    /**
     * Argument to retrive
     *
     * @param mixed $key
     * @return mixed Can return all value types.
     */
    public function offsetGet($key)
    {
        $key = is_string($key) ? $this->getParameterIndexFromName($key) : $key;
        $key = $key === false ? -1 : $key ;
        return isset($this->arguments[$key]) ? $this->arguments[$key] : null;
    }

    /**
     * Argument to set
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->arguments[$this->getNextLeftIndex()] = $value;
        } else {
            $key = is_string($key) ? $this->getParameterIndexFromName($key) : $key;
            if ($key !== false) {
                $this->arguments[$key] = $value;
            }
        }
    }

    /**
     * Argument to unset
     *
     * @param mixed $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $key = is_string($key) ? $this->getParameterIndexFromName($key) : $key;
        if ($key !== false) {
            unset($this->arguments[$key]);
        }
    }
}
