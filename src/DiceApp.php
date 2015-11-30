<?php
namespace MeadSteve\DiceApi;

use League\CommonMark\CommonMarkConverter;
use MeadSteve\DiceApi\Counters\DiceCounter;
use MeadSteve\DiceApi\RequestHandler\DiceRequestHandler;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class DiceApp extends App
{
    private $diceCounter;
    private $diceRequestHandler;

    public function __construct(DiceRequestHandler $diceRequestHandler, DiceCounter $diceCounter)
    {
        parent::__construct();

        $this->diceRequestHandler = $diceRequestHandler;
        $this->diceCounter = $diceCounter;

        $this->setupRoutes();
    }

    public function index(Request $request, Response $response)
    {
        $indexFilePath = __DIR__ . "/generated-index.html";
        if (!file_exists($indexFilePath)) {
            $converter = new CommonMarkConverter();
            $indexBody = $converter->convertToHtml(file_get_contents(__DIR__ . "/../README.md"));
            $indexContent = file_get_contents(__DIR__ . "/../www/templates/index.html");
            $indexContent = str_replace("{{body}}", $indexBody, $indexContent);
            file_put_contents($indexFilePath, $indexContent);
            $response->write($indexContent);
        } else {
            $indexContent = file_get_contents($indexFilePath);
            $response->write($indexContent);
        }
        return $response;
    }

    public function diceStats(Request $request, Response $response)
    {
        $countData = $this->diceCounter->getCounts();
        return $response->write(json_encode($countData))
            ->withHeader("Content-Type", "application/json");
    }

    private function setupRoutes()
    {
        $this->get("/", [$this, 'index']);
        $this->get("/dice-stats", [$this, 'diceStats']);
        $this->get("{dice:(?:/[0-9]*[dD][0-9]+)+/?}", [$this->diceRequestHandler, 'getDice']);

        $this->get("/html{dice:(?:/[0-9]*[dD][0-9]+)+/?}", function (Request $request, $response, $args) {
            return $this->diceRequestHandler->getDice(
                $request->withHeader('accept', 'text/html'),
                $response,
                $args
            );
        });
        $this->get("/json{dice:(?:/[0-9]*[dD][0-9]+)+/?}", function (Request $request, $response, $args) {
            return $this->diceRequestHandler->getDice(
                $request->withHeader('accept', 'application/json'),
                $response,
                $args
            );
        });
    }
}
