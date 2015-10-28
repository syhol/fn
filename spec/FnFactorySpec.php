<?php

namespace spec\Syhol\Fn;

use Exception;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Syhol\Fn\Fn;

class FnFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Syhol\Fn\FnFactory');
    }

    function it_creates_fn_from_builtin()
    {
        $this->parse('min')->shouldHaveType(Fn::class);
    }

    function it_creates_fn_from_user_defined_function()
    {
        $this->parse('spec\Syhol\Fn\test_user_defined_fn')->shouldHaveType(Fn::class);
    }

    function it_creates_fn_from_builtin_method()
    {
        $directory = dir(".");
        $this->parse([$directory, 'read'])->shouldHaveType(Fn::class);
    }

    function it_creates_fn_from_user_defined_method()
    {
        $object = new TestClass();
        $this->parse([$object, 'test'])->shouldHaveType(Fn::class);
    }

    function it_creates_fn_from_user_defined_static_method()
    {
        $this->parse(TestClass::class . '::staticTest')->shouldHaveType(Fn::class);
    }

    function it_creates_fn_from_magic_method()
    {
        $object = new MagicClass();
        $this->parse([$object, '_test'])->shouldHaveType(Fn::class);
    }

    function it_creates_fn_from_user_defined_static_magic_method()
    {
        $this->parse(MagicClass::class . '::staticTest')->shouldHaveType(Fn::class);
    }

    function it_creates_fn_from_invoke_object()
    {
        $object = new InvokeClass();
        $this->parse($object)->shouldHaveType(Fn::class);
    }

    function it_creates_fn_from_closure()
    {
        $this->parse(function($arg){ return 1 + $arg; })->shouldHaveType(Fn::class);
    }
//
//    function it_throws_on_non_static_method_with_class_name()
//    {
//        $this->shouldThrow(Exception::class)->duringParse([TestClass::class, 'test']);
//    }
//
//    function it_throws_on_non_static_method_with_class_name_string()
//    {
//        $this->shouldThrow(Exception::class)->duringParse(TestClass::class . '::test');
//    }
}
