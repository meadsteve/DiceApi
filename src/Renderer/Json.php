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
    public function contentType()
    {
        return "application/json";
    }

    private function diceAsAssocArrays(array $diceCollection)
    {
        return array_map(function (Dice $dice) {
            return [
                "value" => $dice->roll(),
                "size" => "d" . $dice->size()
            ];
        }, $diceCollection);
    }
}
