<?php

namespace spec\MeadSteve\DiceApi;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BasicDiceSpec extends ObjectBehavior
{
    private $diceSize = 6;

    function let()
    {
        $this->beConstructedWith($this->diceSize);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('MeadSteve\DiceApi\BasicDice');
        $this->shouldHaveType('MeadSteve\DiceApi\Dice');
    }

    function it_rolls_an_integer()
    {
        $this->roll()->shouldBeInteger();
    }
}
