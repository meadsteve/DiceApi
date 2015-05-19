<?php

namespace MeadSteve\DiceApi\Renderer;

class RendererFactory
{
    private $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $acceptTypes
     * @return DiceRenderer
     * @throws UnknownRendererException
     */
    public function newForAcceptType($acceptTypes)
    {
        foreach (explode(",", $acceptTypes) as $acceptType) {
            switch ($acceptType) {
                case "application/json":
                    return new Json();
                    break;
                case "text/html":
                    return new Html($this->baseUrl);
                    break;
            }
        }
        throw new UnknownRendererException;
    }
}
