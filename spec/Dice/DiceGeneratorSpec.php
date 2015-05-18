<?php

namespace spec\MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\BasicDice;
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
        $this->diceFromUrlString("/d6/")->shouldBeLike([new BasicDice(6)]);
    }

    function it_returns_many_dice_from_a_url_separated_by_slashes()
    {
        $this->diceFromUrlString("/d6/d20")->shouldBeLike([new BasicDice(6), new BasicDice(20)]);
    }

    function it_returns_many_dice_from_a_single_piece_of_a_url()
    {
        $this->diceFromUrlString("/2d4")->shouldBeLike([new BasicDice(4), new BasicDice(4)]);
    }
}
