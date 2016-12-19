<?php

namespace spec\MeadSteve\DiceApi;

use MeadSteve\DiceApi\BasicDice;
use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\Dice\Factories\DiceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UrlDiceGeneratorSpec extends ObjectBehavior
{
    function let(DiceFactory $diceFactory)
    {
        $this->beConstructedWith($diceFactory);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(\MeadSteve\DiceApi\UrlDiceGenerator::class);
    }

    function it_returns_a_single_dice_from_a_url(DiceFactory $diceFactory)
    {
        $diceFactory->buildDice('6', 1)->willReturn([new BasicDice(6)]);
        $this->diceFromUrlString("/d6/")->shouldBeLike([new BasicDice(6)]);
    }

    function it_returns_many_dice_from_a_url_separated_by_slashes(DiceFactory $diceFactory)
    {
        $diceFactory->buildDice('6', 1)->willReturn([new BasicDice(6)]);
        $diceFactory->buildDice('20', 1)->willReturn([new BasicDice(20)]);

        $this->diceFromUrlString("/d6/d20")->shouldBeLike([new BasicDice(6), new BasicDice(20)]);
    }

    function it_returns_many_dice_from_a_single_piece_of_a_url(DiceFactory $diceFactory)
    {
        $diceFactory->buildDice('4', 2)->willReturn([new BasicDice(4), new BasicDice(4)]);
        $this->diceFromUrlString("/2d4")->shouldBeLike([new BasicDice(4), new BasicDice(4)]);
    }

    function it_returns_many_dice_and_single_dice_from_longer_urls(DiceFactory $diceFactory)
    {
        $diceFactory->buildDice('4', 2)->willReturn([new BasicDice(4), new BasicDice(4)]);
        $diceFactory->buildDice('6', 1)->willReturn([new BasicDice(6)]);
        $diceFactory->buildDice('20', 2)->willReturn([new BasicDice(20), new BasicDice(20)]);

        $this->diceFromUrlString("/2d4/d6/2d20")->shouldBeLike([new BasicDice(4), new BasicDice(4), new BasicDice(6), new BasicDice(20), new BasicDice(20)]);
    }
}
