<?php

namespace spec\Syhol\Fn;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use ReflectionFunction;
use Syhol\Fn\Fn;

class FnSpec extends ObjectBehavior
{
    function let(ReflectionFunction $reflect)
    {
        $test = function(){ return 'foo:' . implode(',', func_get_args()); };
        $this->beConstructedWith($test, $reflect);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Syhol\Fn\Fn');
    }

    function it_calls_the_subject_when_invoked_and_returns_its_response(ReflectionFunction $reflect)
    {
        $this()->shouldBe('foo:');
    }

    function it_builds_arguments_passed_in()
    {
        $this->buildArguments([1, 2])->shouldBeArray('array');
    }

    function it_should_apply_partial_left()
    {
        $this->partialLeft([1, 2])->shouldHaveType(Fn::class);
    }

    function it_should_check_implementation_and_fail(ReflectionFunction $reflect)
    {
        $interface = new \ReflectionMethod(TestInterface::class, 'test');

        $reflect->getParameters()->willReturn(array_slice($interface->getParameters(), 0, -1));
        $this->implementsInterface($interface)->shouldBe(false);
    }

    function it_should_check_implementation(ReflectionFunction $reflect)
    {
        $interface = new \ReflectionMethod(TestInterface::class, 'test');

        $reflect->getParameters()->willReturn($interface->getParameters());
        $this->implementsInterface($interface)->shouldBe(true);
    }

    function it_should_compose(Fn $other)
    {
        $other->pipe($this)->willReturn($other)->shouldBeCalled();
        $this->compose($other)->shouldHaveType(Fn::class);

        $pipe = $this->compose(fn(function ($a) { return 'boz:' . $a; }));
        $pipe->shouldHaveType(Fn::class);
        $pipe->__invoke('qux')->shouldReturn('foo:boz:qux');
    }

    function it_should_pipe()
    {
        $pipe = $this->pipe(fn(function ($a) { return $a . 'boz'; }));
        $pipe->shouldHaveType(Fn::class);
        $pipe->__invoke('qux:')->shouldReturn('foo:qux:boz');
    }
}
