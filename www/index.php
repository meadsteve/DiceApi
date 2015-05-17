<?php

use MeadSteve\DiceApi\Counters\RedisCounter;
use MeadSteve\DiceApi\Dice\DiceGenerator;
use MeadSteve\DiceApi\DiceApp;
use Predis\Client;

require __DIR__ . "/../vendor/autoload.php";

$diceGenerator = new DiceGenerator();

$_ENV['REDIS_URL'] = "redis://h:pele4juml124ic26bv84rj4akif@ec2-107-22-167-67.compute-1.amazonaws.com:6629";

$redis = new Client([
    'host' => parse_url($_ENV['REDIS_URL'], PHP_URL_HOST),
    'port' => parse_url($_ENV['REDIS_URL'], PHP_URL_PORT),
    'password' => parse_url($_ENV['REDIS_URL'], PHP_URL_PASS),
]);
$diceCounter = new RedisCounter($redis);

$app = new DiceApp($diceGenerator, $diceCounter);
$app->run();