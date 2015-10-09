<?php

namespace Syhol\Fn;

use ReflectionMethod;
use ReflectionFunction;
use Exception;

class FnFactory
{
    public function parse(callable $callable)
    {
        if ($callable instanceof Fn) return $callable;
        
        $reflection = null;

        if (is_string($callable) && strpos($callable, '::') !== false)
            $callable = explode('::', $this->callable, 2);

        if (is_array($callable) && count($callable) === 2) {
            $reflection = $this->parseMethod($callable);
        } else {
            $reflection = $this->parseOther($callable);
        }

        if ( ! ($reflection instanceof ReflectionFunctionAbstract) )
            throw new Exception('Could not parse function');
            
        return new Fn($callable, $reflection);
    }
    
    private function parseMethod(callable $callable)
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
    
    private function parseOther(callable $callable)
    {
        if ($callable instanceof Closure || is_string($callable))
            return new ReflectionFunction($callable);
            
        if (is_object($callable) && method_exists($callable, '__invoke'))
            return new ReflectionMethod($callable, '__invoke');
    }
}
