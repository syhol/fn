<?php

namespace Syhol\Fn;

use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionFunction;
use Exception;

class FnFactory
{
    /**
     * @param callable $callable
     * @return Fn
     * @throws Exception
     */
    public function parse(callable $callable)
    {
        if ($callable instanceof Fn) return $callable;
        
        $reflection = null;

        if (is_string($callable) && strpos($callable, '::') !== false)
            $callable = explode('::', $this->callable, 2);

        if (is_array($callable) && count($callable) === 2) {
            $reflection = $this->parseMethod($callable);
        } elseif ($callable instanceof Closure || is_string($callable)) {
            $reflection = new ReflectionFunction($callable);
        } elseif (is_object($callable) && method_exists($callable, '__invoke')) {
            $reflection = new ReflectionMethod($callable, '__invoke');
        }

        if ( ! ($reflection instanceof ReflectionFunctionAbstract) )
            throw new Exception('Could not parse function');
            
        return new Fn($callable, $reflection);
    }

    /**
     * @param array $fns
     * @return Fn
     * @throws Exception
     */
    public function poly(array $fns)
    {
        $fns = array_map([$this, 'parse'], $fns);

        return $this->parse(function() use ($fns) {
            throw new Exception('Not implemented');
        });
    }

    /**
     * @param array $callable
     * @return ReflectionMethod
     * @throws Exception
     */
    private function parseMethod(array $callable)
    {
        list($class, $method) = array_values($callable);
        
        if (is_string($class) && ! method_exists($class, $method))
            $method = '__callStatic';
        
        if (is_object($class) && ! method_exists($class, $method))
            $method = '__call';
            
        $reflection = new ReflectionMethod($class, $method);
        
        if ( ! $reflection->isStatic() && ! is_object($class) )
            throw new Exception('Calling non static method without an instance');
            
        return $reflection;
    }
}