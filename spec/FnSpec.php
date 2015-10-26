<?php

namespace spec\Syhol\Fn;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FnSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(function(){}, new \ReflectionFunction(function(){}));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Syhol\Fn\Fn');
    }
}
