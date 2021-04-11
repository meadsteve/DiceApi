<?php
namespace MeadSteve\DiceApi;

use League\CommonMark\CommonMarkConverter;
use MeadSteve\DiceApi\Counters\DiceCounter;
use MeadSteve\DiceApi\RequestHandler\DiceRequestHandler;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

use function foo\func;
use function MeadSteve\DiceApi\Helpers\file_contents;
use function MeadSteve\DiceApi\Helpers\json_encode;

class DiceApp extends App
{
    const DICE_PATH_REGEX = "{dice:(?:/[0-9]*[dD][^\/]+)+/?}";
    private const INDEX_FILE_PATH = __DIR__ . "/generated-index.html";

    /**
     * @var DiceCounter
     */
    private $diceCounter;

    /**
     * @var DiceRequestHandler
     */
    private $diceRequestHandler;

    public function __construct(DiceRequestHandler $diceRequestHandler, DiceCounter $diceCounter)
    {
        parent::__construct();

        $this->diceRequestHandler = $diceRequestHandler;
        $this->diceCounter = $diceCounter;

        $this->setupRoutes();
        $this->seedMiddlewareStack();
    }

    /**
     * Builds a nice index file from the repo's README.md
     * Saves it and returns it.
     */
    public static function buildIndex(): string
    {
        $converter = new CommonMarkConverter();
        $indexBody = $converter->convertToHtml(file_contents(__DIR__ . "/../README.md"));
        $indexContent = file_contents(__DIR__ . "/../www/templates/index.html");
        $indexContent = str_replace("{{body}}", $indexBody, $indexContent);
        file_put_contents(self::INDEX_FILE_PATH, $indexContent);
        return $indexContent;
    }

    public function index(Request $request, Response $response): Response
    {
        if (!file_exists(self::INDEX_FILE_PATH)) {
            $indexContent = self::buildIndex();
        } else {
            $indexContent = file_contents(self::INDEX_FILE_PATH);
        }
        $response->write($indexContent);
        return $response;
    }

    public function diceStats(Request $request, Response $response): Response
    {
        $countData = $this->diceCounter->getCounts();
        return $response->write(json_encode($countData))
            ->withHeader("Content-Type", "application/json");
    }

    public function healthCheck(Request $request, Response $response): Response
    {
        return $response->write("ok");
    }

    private function setupRoutes(): void
    {
        $diceRequestHandler = $this->diceRequestHandler;

        $this->get("/", [$this, 'index']);
        $this->get("/dice-stats", [$this, 'diceStats']);
        $this->get("/health-check", [$this, 'healthCheck']);
        $this->get(self::DICE_PATH_REGEX, $diceRequestHandler);

        foreach ($this->diceRequestHandler->contentTypesForPaths() as $path => $contentType) {
            $this->addCustomRoute($path, $contentType);
        }
    }

    private function addCustomRoute(string $path, string $contentType): void
    {
        $diceRequestHandler = $this->diceRequestHandler;
        $this->get(
            "/{$path}" . self::DICE_PATH_REGEX,
            function (Request $request, Response $response, $args) use ($diceRequestHandler, $contentType): Response {
                return $diceRequestHandler->getDice(
                    $request->withHeader('accept', $contentType),
                    $response,
                    $args
                );
            }
        );
    }
}
