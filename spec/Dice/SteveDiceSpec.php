<?php

namespace spec\MeadSteve\DiceApi\Dice;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SteveDiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(\MeadSteve\DiceApi\Dice\SteveDice::class);
        $this->shouldHaveType(\MeadSteve\DiceApi\Dice::class);
    }

    function it_has_a_name_of_dSTEVE()
    {
        $this->name()->shouldBe("dSTEVE");
    }

    function it_always_rolls_steve()
    {
        $this->roll()->shouldBe("Steve");
    }
}
