<?php

namespace MeadSteve\DiceApi\Renderer;

use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\Protos\V1\Response;

class Protobuf implements DiceRenderer
{

    /**
     * @param Dice[] $diceCollection
     * @return string
     */
    public function renderDice(array $diceCollection)
    {
        $roll = new Response();
        $roll->setDice($this->diceAsProtos($diceCollection));
        return $roll->serializeToString();
    }
    /**
     * @return string
     */
    public function contentType() : string
    {
        return "application/x-protobuf";
    }

    public function urlPrefix(): string
    {
        return "protobuf";
    }

    private function diceAsProtos(array $diceCollection)
    {
        return array_map(function (Dice $dice) {
            $proto = new \MeadSteve\DiceApi\Protos\V1\Dice();
            $proto->setValue($dice->roll());
            $proto->setName($dice->name());
            return $proto;
        }, $diceCollection);
    }
}
