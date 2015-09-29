<?php

namespace Syhol\Fn;

use Exception;

class FnFactory
{
    public function parse(callable $callable)
    {
        $reflection = null;

        if (is_string($callable) && strpos($callable, '::') !== false)
            $callable = explode('::', $this->callable, 2);

        if (is_array($callable) && count($callable) === 2)
        {
            list($class, $method) = array_values($callable);
            if (is_string($class) && ! method_exists($class, $method)) $method = '__callStatic';
            if (is_object($class) && ! method_exists($class, $method)) $method = '__call';
            $reflection = new ReflectionMethod($class, $method);
        } 

        if ($callable instanceof Closure || is_string($callable))
            $reflection = new ReflectionFunction($callable);

        if (is_object($callable) && method_exists($callable, '__invoke'))
            $reflection = new ReflectionMethod($callable, '__invoke');

        $parameters = $reflection ? $this->reflection->getParameters() : [] ;

        return new Fn($callable, $parameters);
    }
}