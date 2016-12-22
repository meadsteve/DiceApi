<?php
namespace MeadSteve\DiceApi\Renderer;

use MeadSteve\DiceApi\Dice;

interface DiceRenderer
{
    /**
     * @param Dice[] $diceCollection
     * @return mixed
     */
    public function renderDice(array $diceCollection);

    public function contentType() : string;

    public function urlPrefix() : string;
}
