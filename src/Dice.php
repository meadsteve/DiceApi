<?php


namespace MeadSteve\DiceApi;


class Dice implements \JsonSerializable
{

    private $size;

    public function __construct($size)
    {
        $this->size = $size;
    }

    public function size()
    {
        return $this->size;
    }

    public function jsonSerialize()
    {
        return [
            "value" => $this->roll(),
            "size" => "d" . $this->size()
        ];
    }

    public function roll()
    {
        return (int) rand(1, $this->size);
    }
}
