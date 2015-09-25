<?php

namespace Syhol\Fun;

use Closure;
use ReflectionFunction;
use ReflectionMethod;
use Exception;

class Fun
{
	protected $reflection = [];

	protected $orderedArguments = [];

	protected $namedArguments = [];

	protected $callable;

	public function __construct(callable $callable)
	{
		$this->callable = $callable;

		$this->parseCallable($this->callable);
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

	public function reflect()
	{
		return $this->reflection;
	}

	public function partialAt($index, $arg)
	{
		
	}


	public function partialFor($name, $arg)
	{
		
	}

	public function partialLeft()
	{
		
	}

	public function partialRight()
	{
		
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

	public function bind($scope)
	{
		
	}

	protected function parseCallable(callable $callable)
	{
		if (is_string($callable) && strpos($callable, '::') !== false)
			$callable = explode('::', $this->callable);

		if (is_array($callable) && count($callable) === 2) 
			return $this->reflection = new ReflectionMethod(array_shift($callable), array_shift($callable));

		if ($callable instanceof Closure || is_string($callable))
			return $this->reflection = new ReflectionFunction($callable);

		if (is_object($callable) && method_exists($callable, '__invoke'))
			return $this->reflection = new ReflectionMethod($callable, '__invoke');

		throw new Exception('callable could not be parsed');
	}
}
