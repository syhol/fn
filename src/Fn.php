<?php

namespace Syhol\Fn;

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

	public function impelments($interface)
	{
		return true ? true : false;
	}

	public function unbindArgs()
	{
		
	}
}
