<?php

namespace MeadSteve\DiceApi\Renderer;

class Json implements DiceRenderer
{

    public function renderDice(array $diceCollection)
    {
        $data = [
            "success" => true,
            "dice" => $diceCollection
        ];
        return json_encode($data);
    }
}
