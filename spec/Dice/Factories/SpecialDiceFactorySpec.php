<?php

namespace spec\MeadSteve\DiceApi\Dice\Factories;

use MeadSteve\DiceApi\BasicDice;
use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\Dice\Factories\NumericDiceFactory;
use MeadSteve\DiceApi\Dice\Factories\SpecialDiceFactory;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SpecialDiceFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SpecialDiceFactory::class);
    }

    function it_returns_a_steve_dice()
    {
        $this->buildDice("STeVe", 1)->shouldBeLike([new Dice\SteveDice()]);
    }
}
