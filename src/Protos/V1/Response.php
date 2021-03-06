<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: protos/dice.proto

namespace MeadSteve\DiceApi\Protos\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>MeadSteve.DiceApi.Protos.V1.Response</code>
 */
class Response extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .MeadSteve.DiceApi.Protos.V1.Dice dice = 1;</code>
     */
    private $dice;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \MeadSteve\DiceApi\Protos\V1\Dice[]|\Google\Protobuf\Internal\RepeatedField $dice
     * }
     */
    public function __construct($data = NULL) {
        \MeadSteve\DiceApi\Protos\V1\Meta\Dice::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .MeadSteve.DiceApi.Protos.V1.Dice dice = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getDice()
    {
        return $this->dice;
    }

    /**
     * Generated from protobuf field <code>repeated .MeadSteve.DiceApi.Protos.V1.Dice dice = 1;</code>
     * @param \MeadSteve\DiceApi\Protos\V1\Dice[] | \Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setDice($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \MeadSteve\DiceApi\Protos\V1\Dice::class);
        $this->dice = $arr;

        return $this;
    }

}

