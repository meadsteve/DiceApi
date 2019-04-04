<?php

namespace MeadSteve\DiceApi\Renderer;

use MeadSteve\DiceApi\Dice;

class Html implements DiceRenderer
{
    const SUPPORTED_DICE = ["d6", "d20"];

    /**
     * @var string
     */
    private $urlRoot;

    public function __construct(string $urlRoot)
    {
        $this->urlRoot = $urlRoot;
    }

    public function renderDice(array $diceCollection)
    {
        $diceHtmlParts = array_map([$this, 'htmlForSingleDice'], $diceCollection);
        return implode('', $diceHtmlParts);
    }

    public function contentType() : string
    {
        return "text/html";
    }

    public function urlPrefix(): string
    {
        return "html";
    }

    public function htmlForSingleDice(Dice $dice): string
    {
        $name = $dice->name();
        if (!in_array($name, self::SUPPORTED_DICE)) {
            $supportedDice = implode(", ", self::SUPPORTED_DICE);
            throw new UnrenderableDiceException("Only the following can be rendered as html: $supportedDice");
        }
        $roll = $dice->roll();
        $url = "{$this->urlRoot}/images/poorly-drawn/{$name}/{$roll}.png";
        return '<img src="' . $url . '" />';
    }
}
