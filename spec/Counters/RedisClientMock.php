<?php

namespace spec\MeadSteve\DiceApi\Counters;

use Predis\Client;

class RedisClient extends Client
{
    public function incr($key)
    {
        return true;
    }
}
 