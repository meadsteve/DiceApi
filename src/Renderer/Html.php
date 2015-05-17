<?php

namespace MeadSteve\DiceApi\Renderer;

use MeadSteve\DiceApi\Dice;

class Html implements DiceRenderer
{
    private $urlRoot;

    public function __construct($urlRoot)
    {
        $this->urlRoot = $urlRoot;
    }

    public function renderDice(array $diceCollection)
    {
        $diceHtmlParts = array_map([$this, 'htmlForSingleDice'], $diceCollection);
        return implode('', $diceHtmlParts);
    }

    public function htmlForSingleDice(Dice $dice)
    {
        $size = $dice->size();
        if ($size != 6) {
            throw new UnrenderableDiceException("Currently only d6 can be rendered as html");
        }
        $roll = $dice->roll();
        $url = "{$this->urlRoot}/images/poorly-drawn/d{$size}.{$roll}.png";
        return '<img src="' . $url . '" />';
    }
}
