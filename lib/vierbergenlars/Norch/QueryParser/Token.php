<?php

namespace vierbergenlars\Norch\QueryParser;

class Token
{

    const T_NONE = 0;

    const T_FIELD_NAME = 1;

    const T_STRING = 2;

    const T_FIELD_WEIGHT = 3;

    const T_FIELD_VALUE = 4;

    const T_FIELD_SEARCH = 5;

    protected $type;

    protected $data = null;

    protected $startPos;

    function __construct($type, $startPos)
    {
        $this->type = $type;
        $this->startPos = $startPos;
    }

    function addData($data)
    {
        $this->data.=$data;
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function setTypeIfNone($type)
    {
        if($this->type == self::T_NONE)
            $this->type = $type;
    }

    function isTypeNoneOr($type)
    {
        return ($this->type == self::T_NONE || $this->type == $type);
    }

    function getType()
    {
        return $this->type;
    }

    function getData()
    {
        return $this->data;
    }

    function getStartPosition()
    {
        return $this->startPos;
    }

    static function getName($token)
    {
        $refl = new \ReflectionClass(__CLASS__);
        $constants = $refl->getConstants();
        $token_name = array_search($token, $constants);
        if($token_name)
            return $token_name;
        return 'UNKNOWN_TOKEN';
    }

}
