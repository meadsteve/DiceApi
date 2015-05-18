<?php

namespace MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\BasicDice;

class DiceGenerator
{
    public function diceFromUrlString($urlString)
    {
        $parts = explode("/", $urlString);
        $dice = [];
        foreach ($parts as $part) {
            $data = [];
            $valid = preg_match("/(?P<count>[0-9]+)?d(?P<size>[0-9]+)/i", $part, $data);
            if ($valid) {
                if ((strlen($data["size"]) > 4) || ($data["size"] > 9000)) {
                    throw new UncreatableDiceException("Only dice with a power level less than 9000 can be created.");
                }
                if (!$data["count"]) {
                    $data["count"] = 1;
                }
                for ($i = 0; $i < $data["count"]; $i++) {
                    $dice[] = new BasicDice($data["size"]);
                }
            }
        }
        return $dice;
    }
}
