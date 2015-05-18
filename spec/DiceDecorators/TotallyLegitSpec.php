<?php

namespace spec\MeadSteve\DiceApi\DiceDecorators;

use MeadSteve\DiceApi\Dice;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TotallyLegitSpec extends ObjectBehavior
{
    private $definiteRoll = 5;

    function let(Dice $dice)
    {
        $this->beConstructedWith($dice, $this->definiteRoll);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MeadSteve\DiceApi\DiceDecorators\TotallyLegit');
        $this->shouldHaveType('MeadSteve\DiceApi\Dice');
    }

    function it_always_rolls_the_requested_number()
    {
        $this->roll()->shouldEqual($this->definiteRoll);
    }

    function it_returns_the_size_of_the_base_dice(Dice $dice)
    {
        $size = 6;
        $dice->size()->willReturn($size);
        $this->size()->shouldEqual($size);
    }
}
