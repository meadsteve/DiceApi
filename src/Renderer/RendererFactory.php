<?php

namespace MeadSteve\DiceApi\Renderer;

class RendererFactory
{
    /**
     * @param string $acceptType
     * @return DiceRenderer
     * @throws UnknownRendererException
     */
    public function newForAcceptType($acceptType)
    {
        switch ($acceptType) {
            case "application/json":
                return new Json();
                break;
            case "text/html":
                return new Html('http://' . $_SERVER['HTTP_HOST']);
                break;
            default:
                throw new UnknownRendererException;
                break;
        }
    }
}
