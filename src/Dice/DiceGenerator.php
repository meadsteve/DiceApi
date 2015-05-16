<?php


namespace MeadSteve\DiceApi\Dice;


use MeadSteve\DiceApi\Dice;

class DiceGenerator
{


    public function diceFromUrlString($urlString)
    {
        $parts = explode("/", $urlString);
        $dice = [];
        foreach($parts as $part) {
            $data = [];
            $valid = preg_match("/(?P<count>[0-9]+)?d(?P<size>[0-9]+)/i", $part, $data);
            if ($valid) {
                if (!$data["count"]) {
                    $data["count"] = 1;
                }
                for($i = 0; $i < $data["count"]; $i++) {
                    $dice[] = new Dice($data["size"]);
                }
            }
        }
        return $dice;
    }
}
