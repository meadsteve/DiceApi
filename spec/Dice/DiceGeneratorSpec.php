<?php

namespace spec\MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\Dice;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DiceGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('MeadSteve\DiceApi\Dice\DiceGenerator');
    }

    function it_returns_a_single_dice_from_a_url()
    {
        $this->diceFromUrlString("/d6/")->shouldBeLike([new Dice(6)]);
    }

    function it_returns_many_dice_from_a_url_separated_by_slashes()
    {
        $this->diceFromUrlString("/d6/d20")->shouldBeLike([new Dice(6), new Dice(20)]);
    }

    function it_returns_many_dice_from_a_single_piece_of_a_url()
    {
        $this->diceFromUrlString("/2d4")->shouldBeLike([new Dice(4), new Dice(4)]);
    }
}
