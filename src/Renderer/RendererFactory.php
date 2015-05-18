<?php

namespace MeadSteve\DiceApi\Renderer;

class RendererFactory
{
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
                    return new Html('http://' . $_SERVER['HTTP_HOST']);
                    break;
            }
        }
        throw new UnknownRendererException;
    }
}
