<?php

namespace MeadSteve\DiceApi;

use MeadSteve\DiceApi\Dice\Factories\DiceFactory;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;

class UrlDiceGenerator
{
    /**
     * @var DiceFactory
     */
    private $diceFactory;

    public function __construct(DiceFactory $diceFactory)
    {
        $this->diceFactory = $diceFactory;
    }

    /**
     * @param string $urlString
     *
     * @throws UncreatableDiceException
     *
     * @return Dice[]
     */
    public function diceFromUrlString(string $urlString): array
    {
        $parts = explode("/", $urlString);
        $parts = array_filter($parts, [$this, 'notBlank']);
        $diceSets = array_map([$this, 'getDiceForPart'], $parts);
        return $this->flattenDiceSets($diceSets);
    }

    /**
     * @param string $part
     *
     * @throws UncreatableDiceException
     *
     * @return Dice[]
     */
    private function getDiceForPart(string $part): array
    {
        $data = $this->parseDiceString($part);
        return $this->diceFactory->buildDice($data["type"], $data["count"]);
    }

    /**
     * @param Dice[][] $diceSets
     * @return Dice[]
     */
    private function flattenDiceSets(array $diceSets): array
    {
        $dice = [];
        foreach ($diceSets as $set) {
            $dice = array_merge($dice, $set);
        }
        return $dice;
    }

    private function parseDiceString(string $part): array
    {
        $data = [];
        $valid = preg_match("/(?P<count>[0-9]+)?d(?P<type>[^\/]+)/i", $part, $data);
        if (!$valid) {
            throw new UncreatableDiceException("Problem creating dice from incorrectly formatted data: " . $part);
        }
        if (!$data["count"]) {
            $data["count"] = 1;
        }
        return $data;
    }

    private function notBlank(string $string)
    {
        return $string !== "";
    }
}
