<?php

use MeadSteve\DiceApi\Counters\NullCounter;
use MeadSteve\DiceApi\Dice\DiceGenerator;
use MeadSteve\DiceApi\DiceApp;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;

class DiceAppTest extends PHPUnit_Framework_TestCase
{
    public function testAppCanRolld6AndReturnJson()
    {
        $app = new DiceApp(
            new DiceGenerator(),
            new NullCounter()
        );

        $request = $this->requestForPath("/json/d6");
        $response = new Response();

        // Invoke app
        $responseOut = $app($request, $response);

        $this->assertEquals($responseOut->getStatusCode(), 200);
    }

    private function requestForPath($path)
    {
        $path = new \Slim\Http\Uri("http", "diceapi.com", null, $path);
        $body = new Body(fopen('php://temp', 'r+'));
        $headers = new \Slim\Http\Headers();
        $request = new Request("GET", $path, $headers, [], [], $body);
        return $request;
    }
}
