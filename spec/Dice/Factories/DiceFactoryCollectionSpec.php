<?php

namespace spec\MeadSteve\DiceApi\Dice\Factories;

use MeadSteve\DiceApi\Dice\BasicDice;
use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\Dice\Factories\DiceFactory;
use MeadSteve\DiceApi\Dice\Factories\NumericDiceFactory;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DiceFactoryCollectionSpec extends ObjectBehavior
{
    function let(DiceFactory $factoryOne, DiceFactory $factoryTwo)
    {
        $this->beConstructedWith([$factoryOne, $factoryTwo]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DiceFactory::class);
    }

    function it_doesnt_handle_types_not_handled_by_its_factories(DiceFactory $factoryOne, DiceFactory $factoryTwo)
    {
        $factoryOne->handlesType('test')->willReturn(false);
        $factoryTwo->handlesType('test')->willReturn(false);

        $this->handlesType('test')->shouldReturn(false);
    }

    function it_handles_types_if_one_factory_does(DiceFactory $factoryOne, DiceFactory $factoryTwo)
    {
        $factoryOne->handlesType('test')->willReturn(false);
        $factoryTwo->handlesType('test')->willReturn(true);

        $this->handlesType('test')->shouldReturn(true);
    }

    function it_delegates_construction_to_its_factories(DiceFactory $factoryOne, DiceFactory $factoryTwo)
    {
        $factoryOne->handlesType('test')->willReturn(false);

        $diceCollection = [new BasicDice(1)];
        $factoryTwo->handlesType('test')->willReturn(true);
        $factoryTwo->buildDice('test', 1)->willReturn($diceCollection);

        $this->buildDice('test', 1)->shouldReturn($diceCollection);
    }

    function it_throws_an_exception_if_asked_to_build_dice_it_cant(DiceFactory $factoryOne, DiceFactory $factoryTwo)
    {
        $factoryOne->handlesType('test')->willReturn(false);
        $factoryTwo->handlesType('test')->willReturn(false);


        $this->shouldThrow(UncreatableDiceException::class)->duringBuildDice('test', 1);
    }
}
