<?php

namespace MeadSteve\DiceApi\Dice\Factories;

use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;

class DiceFactoryCollection implements DiceFactory
{
    /**
     * @var DiceFactory[]
     */
    private $factories;

    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    public function handlesType(string $type): bool
    {
        foreach ($this->factories as $factory) {
            if ($factory->handlesType($type)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $type
     * @param int $number
     * @return Dice[]
     */
    public function buildDice(string $type, int $number): array
    {
        foreach ($this->factories as $factory) {
            if ($factory->handlesType($type)) {
                return $factory->buildDice($type, $number);
            }
        }
        throw new UncreatableDiceException("No idea how to make a d{$type}");
    }
}
