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

    /**
     * @return string
     */
    public function contentType() : string
    {
        return "text/html";
    }

    public function urlPrefix(): string
    {
        return "html";
    }

    public function htmlForSingleDice(Dice $dice)
    {
        $name = $dice->name();
        if ($name != "d6" && $name != "d20") {
            throw new UnrenderableDiceException("Currently only d6 and d20 can be rendered as html");
        }
        $roll = $dice->roll();
        $url = "{$this->urlRoot}/images/poorly-drawn/{$name}/{$roll}.png";
        return '<img src="' . $url . '" />';
    }
}
