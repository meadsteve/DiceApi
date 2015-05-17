<?php

namespace MeadSteve\DiceApi\Counters;

use MeadSteve\DiceApi\Dice;
use Predis\Client;

class RedisCounter
{

    private $redisClient;

    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @param Dice[] $diceCollection
     * @return bool
     */
    public function count(array $diceCollection)
    {
        foreach ($diceCollection as $dice) {
            $this->redisClient->incr('dice-count-d' . $dice->size());
        }
        return true;
    }
}
