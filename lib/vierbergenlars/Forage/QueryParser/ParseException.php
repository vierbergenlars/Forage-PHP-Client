<?php

namespace vierbergenlars\Forage\QueryParser;

class ParseException extends \Exception
{
    public function __construct($message, $string = null, $pos = null)
    {
        if($string) {
            $startpos = $pos - 15;
            $start_diff = 0;
            if($startpos < 0) {
                $start_diff = $startpos;
                $startpos = 0;
            }

            $str = substr($string, $startpos, 30);

            $err_pos_in_str = 15 + $start_diff;


            $message.=' near ';
            $msg_len = strlen($message);
            $message.= $str;
            $message.="\n";
            $i = 0;
            for($i = 0; $i < $msg_len; $i++)
                $message.=' ';
            for($i = 0; $i < $err_pos_in_str; $i++)
                $message.='-';
            $message.='^';
        }
        parent::__construct($message);
    }

}
