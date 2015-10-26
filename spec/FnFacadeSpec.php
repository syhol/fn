<?php

namespace spec\Syhol\Fn;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Syhol\Fn\FnContainer;
use Syhol\Fn\FnFactory;

class FnFacadeSpec extends ObjectBehavior
{
    function let(FnFactory $factory, FnContainer $container)
    {
        $this->beConstructedWith($factory, $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Syhol\Fn\FnFacade');
    }
}
