<?php

namespace spec\MeadSteve\DiceApi\Counters;

use MeadSteve\DiceApi\Dice;
use PhpSpec\ObjectBehavior;
use Predis\Connection\ConnectionException;
use Prophecy\Argument;

require_once __DIR__ . "/RedisClientMock.php";

class RedisCounterSpec extends ObjectBehavior
{
    function let(RedisClient $redisClient)
    {
        $this->beConstructedWith($redisClient);
        $redisClient->hincrby("dice-count", Argument::any(), 1)->willReturn(true);
        $redisClient->hgetall("dice-count")->willReturn([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MeadSteve\DiceApi\Counters\RedisCounter');
    }

    function it_counts_single_dice_as_the_correct_type(Dice $dice, RedisClient $redisClient)
    {
        $dice->name()->willReturn(6);
        $this->count([$dice]);
        $redisClient->hincrby("dice-count", "6", 1)->shouldHaveBeenCalledTimes(1);
    }

    function it_counts_multiple_dice_of_a_single_type(Dice $dice, RedisClient $redisClient)
    {
        $dice->name()->willReturn(6);
        $this->count([$dice, $dice]);
        $redisClient->hincrby("dice-count", "6", 1)->shouldHaveBeenCalledTimes(2);
    }

    function it_counts_multiple_dice_of_multiple_types(Dice $d6, Dice $d4, RedisClient $redisClient)
    {
        $d6->name()->willReturn(6);
        $d4->name()->willReturn(4);
        $this->count([$d6, $d4, $d6]);
        $redisClient->hincrby("dice-count", "6", 1)->shouldHaveBeenCalledTimes(2);
        $redisClient->hincrby("dice-count", "4", 1)->shouldHaveBeenCalledTimes(1);
    }

    function it_ignores_connection_errors(Dice $dice, RedisClient $redisClient)
    {
        $dice->name()->willReturn(6);
        $redisClient->hincrby("dice-count", Argument::any(), 1)->willThrow(ConnectionException::class);
        $this->count([$dice])->shouldReturn(false);
    }

    function it_returns_the_current_counts(RedisClient $redisClient, $diceCountData)
    {
        $redisClient->hgetall("dice-count")->willReturn($diceCountData);
        $this->getCounts()->shouldReturn($diceCountData);
    }
}
