<?php

namespace vierbergenlars\Norch\QueryParser;

class Token
{

    const T_NONE = 'T_NONE';

    const T_FIELD_NAME = 'T_FIELD_NAME';

    const T_STRING = 'T_STRING';

    const T_FIELD_WEIGHT = 'T_FIELD_WEIGHT';

    const T_FIELD_VALUE = 'T_FIELD_VALUE';

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

}
