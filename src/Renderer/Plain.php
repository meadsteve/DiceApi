<?php

namespace MeadSteve\DiceApi\Renderer;

use MeadSteve\DiceApi\Dice;

class Plain implements DiceRenderer
{
    public function renderDice(array $diceCollection)
    {
        $diceHtmlParts = array_map([$this, 'textForSingleDice'], $diceCollection);
        return implode(', ', $diceHtmlParts);
    }

    /**
     * @return string
     */
    public function contentType() : string
    {
        return "text/string";
    }

    public function urlPrefix(): string
    {
        return "string";
    }

    public function textForSingleDice(Dice $dice): string
    {
        return "{$dice->name()} : {$dice->roll()}";
    }
}
