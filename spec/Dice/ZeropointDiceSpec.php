<?php

namespace spec\MeadSteve\DiceApi\Dice;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ZeropointDiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('MeadSteve\DiceApi\Dice\ZeropointDice');
        $this->shouldHaveType('MeadSteve\DiceApi\Dice');
    }

    function it_has_a_size_of_zero()
    {
        $this->size()->shouldBe(0);
    }

    function it_rolls_a_singularity()
    {
        $this->roll()->shouldBe("Singularity");
    }
}
