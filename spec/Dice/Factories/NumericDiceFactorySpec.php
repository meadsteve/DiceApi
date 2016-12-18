<?php

namespace spec\MeadSteve\DiceApi\Dice\Factories;

use MeadSteve\DiceApi\BasicDice;
use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\Dice\Factories\NumericDiceFactory;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NumericDiceFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NumericDiceFactory::class);
    }

    function it_returns_a_single_d6()
    {
        $this->buildDice("6", 1)->shouldBeLike([new BasicDice(6)]);
    }

    function it_returns_multiple_dice()
    {
        $this->buildDice("4", 2)->shouldBeLike([new BasicDice(4), new BasicDice(4)]);
    }

    function it_throws_an_exception_for_anything_over_a_d_9000()
    {
        $this->shouldThrow(UncreatableDiceException::class)->duringBuildDice("9001", 1);
    }

    function it_returns_a_special_singlepoint_dice_when_asked_for_a_d0()
    {
        $this->buildDice("0", 1)->shouldBeLike([new Dice\ZeropointDice()]);
    }
}
