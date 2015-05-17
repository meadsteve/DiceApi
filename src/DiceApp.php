<?php
namespace MeadSteve\DiceApi;

use League\CommonMark\CommonMarkConverter;
use MeadSteve\DiceApi\Dice\DiceGenerator;
use MeadSteve\DiceApi\Renderer\Html;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class DiceApp extends App
{
    private $diceGenerator;

    public function __construct()
    {
        parent::__construct();

        $this->diceGenerator = new DiceGenerator();

        $this->get("/", [$this, 'index']);
        $this->get("{dice:(?:/[0-9]*[dD][0-9]+)+/?}", [$this, 'getDice']);

        $this->get("/html{dice:(?:/[0-9]*[dD][0-9]+)+/?}", function(Request $request, $response, $args) {
            return $this->getDice($request->withAttribute('Accept', 'text/html'), $response, $args);
        });
        $this->get("/json{dice:(?:/[0-9]*[dD][0-9]+)+/?}", function(Request $request, $response, $args) {
            return $this->getDice($request->withAttribute('Accept', 'application/json'), $response, $args);
        });
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

    public function getDice(Request $request, Response $response, $args)
    {
        $diceResponse = $response->withHeader("cache-control", "no-cache");
        $dice = $this->diceGenerator->diceFromUrlString($args['dice']);
        $requestedContentType = $request->getAttribute('Accept', "application/json");
        switch ($requestedContentType) {
            case "application/json":
                $diceResponse = $this->jsonDiceResponse($diceResponse, $dice);
                break;
            case "text/html":
                $diceResponse = $this->htmlDiceResponse($diceResponse, $dice);
                break;
            default:
                $diceResponse = $response->withStatus(406);
                $diceResponse->write("Not sure how to respond with: " . $requestedContentType);
                break;
        }
        return $diceResponse;
    }

    private function jsonDiceResponse(Response $response, array $dice)
    {
        $data = [
            "success" => true,
            "dice" => $dice
        ];
        $response->write(json_encode($data));
        return $response->withHeader("Content-Type", "application/json");
    }

    private function htmlDiceResponse(Response $response, array $dice)
    {
        $renderer = new Html('http://' . $_SERVER['HTTP_HOST']);
        $response->write($renderer->renderDice($dice));
        return $response->withHeader("Content-Type", "text/html");
    }
}
