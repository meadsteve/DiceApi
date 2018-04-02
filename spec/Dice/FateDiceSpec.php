<?php

namespace spec\MeadSteve\DiceApi\Dice;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FateDiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(\MeadSteve\DiceApi\Dice::class);
    }

    function it_has_a_name_of_dFATE()
    {
        $this->name()->shouldBe("dFATE");
    }

    function it_rolls_one_of_the_correct_values()
    {
        $this->roll()->shouldBeOneOf(["+", "-", " "]);
    }

    public function getMatchers(): array
    {
        return [
            'beOneOf' => function ($value, $subject) {
                return in_array($value, $subject);
            },
        ];
    }
}
