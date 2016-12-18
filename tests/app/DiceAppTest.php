<?php

use MeadSteve\DiceApi\Counters\NullCounter;
use MeadSteve\DiceApi\Dice\DiceGenerator;
use MeadSteve\DiceApi\DiceApp;
use MeadSteve\DiceApi\Renderer\RendererFactory;
use MeadSteve\DiceApi\RequestHandler\DiceRequestHandler;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;

class DiceAppTest extends PHPUnit_Framework_TestCase
{
    private $app;


    protected function setUp()
    {
        $diceGenerator = new DiceGenerator();
        $rendererFactory = new RendererFactory('http://test.com');
        $nullCounter = new NullCounter();
        $diceRequestHandler = new DiceRequestHandler($diceGenerator, $rendererFactory, $nullCounter);
        $this->app = new DiceApp(
            $diceRequestHandler,
            $nullCounter
        );
    }

    public function testAppCanRolld6AndReturnJson()
    {
        $request = $this->requestForPath("/json/d6");
        $responseOut = $this->runApp($request);
        $this->assertEquals($responseOut->getStatusCode(), 200);
        $this->assertEquals($responseOut->getHeader("Content-Type")[0], "application/json");
    }

    public function testAppCanRolld6AndReturnHtml()
    {
        $request = $this->requestForPath("/html/d6");
        $responseOut = $this->runApp($request);
        $this->assertEquals($responseOut->getStatusCode(), 200);
        $this->assertEquals($responseOut->getHeader("Content-Type")[0], "text/html");
    }

    public function testAppCanRolld20AndReturnHtml()
    {
        $request = $this->requestForPath("/html/d20");
        $responseOut = $this->runApp($request);
        $this->assertEquals($responseOut->getStatusCode(), 200);
        $this->assertEquals($responseOut->getHeader("Content-Type")[0], "text/html");
    }

    public function testAppCanRolldSteveandReturnJson()
    {
        $request = $this->requestForPath("/json/2dSteve");
        $responseOut = $this->runApp($request);
        $this->assertEquals($responseOut->getStatusCode(), 200);
        $this->assertEquals($responseOut->getHeader("Content-Type")[0], "application/json");
    }

    /**
     * @param $request
     * @return Psr\Http\Message\ResponseInterface
     */
    private function runApp($request)
    {
        $app = $this->app;
        return $app($request, new Response());
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
