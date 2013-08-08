<?php

namespace vierbergenlars\Norch\QueryParser;

class Lexer
{

    /**
     * This class should not be instanciated
     */
    private function __construct()
    {
        ;
    }

    public static function tokenize($string)
    {
        $len = strlen($string);
        $tokens = array();

        $current_token = new Token(Token::T_NONE, 0);
        $i = 0;
        while($i < $len) {
            $c = $string[$i];
            switch($c) {
                case ' ':
                    self::push($tokens, $current_token, $i);
                    break;
                case ':':
                    if($current_token->getData() == null)
                        throw new ParseException('Expected T_FIELD_NAME, got nothing', $string, $i);
                    if(!$current_token->isTypeNoneOr(Token::T_FIELD_NAME))
                        throw new ParseException('Expected T_FIELD_NAME, got ' . Token::getName($current_token->getType()), $string, $i);
                    $current_token->setType(Token::T_FIELD_NAME);
                    self::push($tokens, $current_token, $i);
                    $current_token->setType(Token::T_FIELD_VALUE);
                    break;
                case '^':
                    if($current_token->getData() == null)
                        throw new ParseException('Expected T_FIELD_NAME, got nothing', $string, $i);
                    if(!$current_token->isTypeNoneOr(Token::T_FIELD_NAME))
                        throw new ParseException('Expected T_FIELD_NAME, got ' . Token::getName($current_token->getType()), $string, $i);
                    $current_token->setType(Token::T_FIELD_NAME);
                    $field_token = $current_token;
                    self::push($tokens, $current_token, $i);
                    $current_token->setType(Token::T_FIELD_WEIGHT);
                    self::readInt($current_token, $string, $i);
                    self::push($tokens, $current_token, $i);
                    if($i + 1 < $len && $string[$i + 1] == ':') // Peek one ahead. Duplicate T_FIELD_NAME token if a T_FIELD_VALUE follows.
                        $current_token = $field_token;
                    break;
                case '@':
                    if($current_token->getData() != null)
                        throw new ParseException('Expected nothing, got ' . Token::getName($current_token->getType()), $string, $i);
                    $current_token->setType(Token::T_FIELD_SEARCH);
                    break;
                case '"':
                    if($current_token->getData() == null) {
                        $current_token->setTypeIfNone(Token::T_STRING);
                        self::readEncString($current_token, $string, $i);
                        if($i + 1 < $len && $string[$i + 1] != ' ') // Peek one ahead. Should be empty
                            throw new ParseException('Unexpected T_STRING', $string, $i + 1);
                    } else {
                        throw new ParseException('Unexpected T_STRING', $string, $i);
                    }
                    break;
                default:
                    $current_token->addData($c);
            }
            $i++;
        }
        self::push($tokens, $current_token, $i);
        return $tokens;
    }

    static private function push(&$tokens, &$current_token, $i)
    {
        if($current_token->getData() === null)
            return;
        $current_token->setTypeIfNone(Token::T_STRING);
        $tokens[] = $current_token;
        $current_token = new Token(Token::T_NONE, $i);
    }

    static private function readEncString(Token $current_token, $string, &$i)
    {
        while(++$i < strlen($string)) {
            if($string[$i] != '"') {
                $current_token->addData($string[$i]);
            } else {
                break;
            }
        }
    }

    static private function readInt(Token $current_token, $string, &$i)
    {
        while(++$i < strlen($string)) {
            if(in_array($string[$i], array('0', '1', '2', '3', '4', '5', '6', '7',
                        '8', '9', '-'), true)) {
                $current_token->addData($string[$i]);
            } else {
                $i--;
                break;
            }
        }
    }

}
