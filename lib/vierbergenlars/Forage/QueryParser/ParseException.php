<?php

namespace vierbergenlars\Forage\QueryParser;

class ParseException extends \Exception
{
    public function __construct($message, $string = null, $pos = null)
    {
        if($string) {
            $startPos = $pos - 15;
            $startDiff = 0;
            if($startPos < 0) {
                $startDiff = $startPos;
                $startPos = 0;
            }

            $str = substr($string, $startPos, 30);

            $errPos = 15 + $startDiff;


            $message.=' near ';
            $msgLen = strlen($message);
            $message.= $str;
            $message.="\n";
            $i = 0;
            for($i = 0; $i < $msgLen; $i++)
                $message.=' ';
            for($i = 0; $i < $errPos; $i++)
                $message.='-';
            $message.='^';
        }
        parent::__construct($message);
    }

}
