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

    const DICE_PATH_REGEX = "{dice:(?:/[0-9]*[dD][^\/]+)+/?}";

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
        $diceRequestHandler = $this->diceRequestHandler;

        $this->get("/", [$this, 'index']);
        $this->get("/dice-stats", [$this, 'diceStats']);
        $this->get(self::DICE_PATH_REGEX, [$diceRequestHandler, 'getDice']);

        foreach ($this->diceRequestHandler->contentTypesForPaths() as $path => $contentType) {
            $this->addCustomRoute($path, $contentType);
        }
    }

    private function addCustomRoute($path, $contentType)
    {
        $diceRequestHandler = $this->diceRequestHandler;
        $this->get(
            "/{$path}" . self::DICE_PATH_REGEX,
            function (Request $request, $response, $args) use ($diceRequestHandler, $contentType) {
                return $diceRequestHandler->getDice(
                    $request->withHeader('accept', $contentType),
                    $response,
                    $args
                );
            }
        );
    }
}
