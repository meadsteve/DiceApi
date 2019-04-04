<?php

namespace MeadSteve\DiceApi\Renderer;

use MeadSteve\DiceApi\Dice;

class Json implements DiceRenderer
{

    public function renderDice(array $diceCollection)
    {
        $data = [
            "success" => true,
            "dice" => $this->diceAsAssocArrays($diceCollection)
        ];
        return json_encode($data);
    }

    /**
     * @return string
     */
    public function contentType() : string
    {
        return "application/json";
    }

    public function urlPrefix(): string
    {
        return "json";
    }

    /**
     * @param Dice[] $diceCollection
     * @return string[][]
     */
    private function diceAsAssocArrays(array $diceCollection)
    {
        return array_map(function (Dice $dice): array {
            return [
                "value" => $dice->roll(),
                "type"  => $dice->name()
            ];
        }, $diceCollection);
    }
}
