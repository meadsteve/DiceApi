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

    function it_returns_image_html_for_a_single_d6(Dice $dice)
    {
        $dice->roll()->willReturn(2);
        $dice->size()->willReturn(6);

        $this->renderDice([$dice])->shouldReturn('<img src="URL/images/poorly-drawn/d6/2.png" />');
    }

    function it_returns_image_html_for_a_single_d20(Dice $dice)
    {
        $dice->roll()->willReturn(14);
        $dice->size()->willReturn(20);

        $this->renderDice([$dice])->shouldReturn('<img src="URL/images/poorly-drawn/d20/14.png" />');
    }

    function it_returns_many_images_for_many_d6(Dice $diceOne, Dice $diceTwo)
    {
        $diceOne->roll()->willReturn(2);
        $diceOne->size()->willReturn(6);


        $diceTwo->roll()->willReturn(5);
        $diceTwo->size()->willReturn(6);

        $this->renderDice([$diceOne, $diceTwo])->shouldReturn('<img src="URL/images/poorly-drawn/d6/2.png" /><img src="URL/images/poorly-drawn/d6/5.png" />');
    }

    function it_returns_many_images_for_many_d20(Dice $diceOne, Dice $diceTwo)
    {
        $diceOne->roll()->willReturn(7);
        $diceOne->size()->willReturn(20);


        $diceTwo->roll()->willReturn(12);
        $diceTwo->size()->willReturn(20);

        $this->renderDice([$diceOne, $diceTwo])->shouldReturn('<img src="URL/images/poorly-drawn/d20/7.png" /><img src="URL/images/poorly-drawn/d20/12.png" />');
    }

    function it_returns_many_images_for_d6_and_d20(Dice $diceOne, Dice $diceTwo)
    {
        $diceOne->roll()->willReturn(3);
        $diceOne->size()->willReturn(6);

        $diceTwo->roll()->willReturn(11);
        $diceTwo->size()->willReturn(20);

        $this->renderDice([$diceOne, $diceTwo])->shouldReturn('<img src="URL/images/poorly-drawn/d6/3.png" /><img src="URL/images/poorly-drawn/d20/11.png" />');
    }

    function it_throws_an_exception_if_it_tries_to_render_a_non_d6_or_non_d20(Dice $wrongSizedDice)
    {
        $wrongSizedDice->roll()->willReturn(3);
        $wrongSizedDice->size()->willReturn(5);

        $this->shouldThrow(UnrenderableDiceException::class)->duringRenderDice([$wrongSizedDice]);
    }
}
