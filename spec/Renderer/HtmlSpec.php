<?php

namespace spec\MeadSteve\DiceApi\Renderer;

use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\Renderer\UnrenderableDiceException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HtmlSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith("URL");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MeadSteve\DiceApi\Renderer\Html');
    }

    function it_returns_image_html_for_a_single_dice(Dice $dice)
    {
        $dice->roll()->willReturn(2);
        $dice->size()->willReturn(6);

        $this->renderDice([$dice])->shouldReturn('<img src="URL/images/poorly-drawn/d6.2.png" />');
    }

    function it_returns_many_images_for_many_dice(Dice $diceOne, Dice $diceTwo)
    {
        $diceOne->roll()->willReturn(2);
        $diceOne->size()->willReturn(6);


        $diceTwo->roll()->willReturn(5);
        $diceTwo->size()->willReturn(6);

        $this->renderDice([$diceOne, $diceTwo])->shouldReturn('<img src="URL/images/poorly-drawn/d6.2.png" /><img src="URL/images/poorly-drawn/d6.5.png" />');
    }

    function it_throws_an_exception_if_it_tries_to_render_a_non_d6(Dice $wrongSizedDice)
    {
        $wrongSizedDice->roll()->willReturn(3);
        $wrongSizedDice->size()->willReturn(5);

        $this->shouldThrow(UnrenderableDiceException::class)->duringRenderDice([$wrongSizedDice]);
    }
}
