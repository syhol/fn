<?php

namespace Syhol\Fn;

use Exception;
use ReflectionFunctionAbstract;
use ReflectionParameter;

class ImplementationChecker
{
    /**
     * @var array
     */
    static protected $checkerMethods = [
        'getType',
        'isOptional',
        'isArray',
        'isCallable',
        'isPassedByReference',
        'isVariadic'
    ];

    /**
     * @param $left
     * @param $right
     * @return bool
     */
    public function checkFunctions($left, $right)
    {
        $left = $this->parseInterface($left);
        $right = $this->parseInterface($right);

        $leftParams = $left->getParameters();

        foreach ($right->getParameters() as $key => $rightParam) {
            $leftParam = isset($leftParams[$key]) ? $leftParams[$key] : false;

            // Check the left param exists
            if ($leftParam instanceof ReflectionParameter === false) {
                return false;
            }

            // Check the left param fulfills the interface of the right param
            if ($this->checkParams($leftParam, $rightParam) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ReflectionParameter $left
     * @param ReflectionParameter $right
     * @return bool
     */
    public function checkParams(ReflectionParameter $left, ReflectionParameter $right)
    {
        // If the param has a class, compare the class names
        if ($this->getClass($left) !== $this->getClass($right)) {
            return false;
        }

        // Check a list of methods to ensure the signature is the same
        foreach (static::$checkerMethods as $method) {
            $exists = method_exists($right, $method);
            if ($exists && $right->$method() !== $left->$method()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ReflectionParameter $param
     * @return null|string
     */
    private function getClass(ReflectionParameter $param)
    {
        return $param->getClass() instanceof ReflectionClass
            ? $param->getClass()->getName()
            : null;
    }

    /**
     * @param mixed $interface
     * @return ReflectionFunctionAbstract
     * @throws Exception
     */
    private function parseInterface($interface)
    {
        if ($interface instanceof Fn) {
            return $interface->reflect();
        }

        if ($interface instanceof ReflectionFunctionAbstract) {
            return $interface;
        }

        try {
            return (new FnFactory)->parse($interface);
        } catch (Exception $exception) {
            throw new Exception('Can\'t parse interface');
        }
    }
}