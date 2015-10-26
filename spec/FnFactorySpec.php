<?php

namespace spec\Syhol\Fn;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FnFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Syhol\Fn\FnFactory');
    }
}
