<?php

use MeadSteve\DiceApi\Counters\NullCounter;
use MeadSteve\DiceApi\Dice\DiceGenerator;
use MeadSteve\DiceApi\DiceApp;
use MeadSteve\DiceApi\Renderer\RendererFactory;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;

class DiceAppTest extends PHPUnit_Framework_TestCase
{
    private $app;

    protected function setUp()
    {
        // This is a hack as the response builder uses this global directly
        // sorry.
        $_SERVER['HTTP_HOST'] = "test.com";
        $this->app = new DiceApp(
            new DiceGenerator(),
            new RendererFactory(),
            new NullCounter()
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
