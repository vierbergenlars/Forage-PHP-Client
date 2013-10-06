<?php

namespace vierbergenlars\Forage\QueryParser;

/**
 * @internal
 */
class Lexer
{

    /**
     * This class should not be instanciated
     */
    private function __construct()
    {
        ;
    }

    /**
     * Tokenizes a string
     * @param string $string The string to tokenzie
     * @return array An array of {@link Token}s
     * @throws ParseException
     */
    public static function tokenize($string)
    {
        $len = strlen($string);
        $tokens = array();

        $currentToken = new Token(Token::T_NONE, 0);
        $i = 0;
        while($i < $len) {
            $char = $string[$i];
            switch($char) {
                case '\\': // Escape character
                    if(++$i >= $len)
                        throw new ParseException('Unexpected end of query', $string, $i);
                    $currentToken->addData($string[$i]);
                    break;
                case ' ':
                    self::push($tokens, $currentToken, $i);
                    break;
                case ':':
                    self::tokenizeField($tokens, $currentToken, $string, $i);
                    break;
                case '^':
                    self::tokenizeWeight($tokens, $currentToken, $string, $i);
                    break;
                case '@':
                    self::tokenizeSearchField($currentToken, $string, $i);
                    break;
                case '"':
                    self::tokenizeEncString($currentToken, $string, $i);
                    break;
                default:
                    $currentToken->addData($char);
            }
            $i++;
        }
        self::push($tokens, $currentToken, $i);
        return $tokens;
    }

    /**
     * Parses an encapsulated string
     *
     * @param Token $currentToken
     * @param string $string
     * @param int $i
     * @throws ParseException
     */
    static private function tokenizeEncString($currentToken, $string, &$i)
    {
        if($currentToken->getData() == null) {
            $currentToken->setTypeIfNone(Token::T_STRING);
            self::readEncString($currentToken, $string, $i);
            if($i + 1 < strlen($string) && $string[$i + 1] != ' ') // Peek one ahead. Should be empty
                throw new ParseException('Unexpected T_STRING', $string, $i + 1);
        } else {
            throw new ParseException('Unexpected T_STRING', $string, $i);
        }
    }

    /**
     * Parses a field weight
     *
     * @param array $tokens
     * @param Token $currentToken
     * @param string $string
     * @param int $i
     * @throws ParseException
     */
    static private function tokenizeWeight(&$tokens, &$currentToken, $string, &$i)
    {
        if($currentToken->getData() == null)
            throw new ParseException('Expected T_FIELD_NAME, got nothing', $string, $i);
        if(!$currentToken->isTypeNoneOr(Token::T_FIELD_NAME))
            throw new ParseException('Expected T_FIELD_NAME, got ' . Token::getName($currentToken->getType()), $string, $i);
        $currentToken->setType(Token::T_FIELD_NAME);
        $fieldToken = $currentToken;
        self::push($tokens, $currentToken, $i);
        $currentToken->setType(Token::T_FIELD_WEIGHT);
        self::readInt($currentToken, $string, $i);
        self::push($tokens, $currentToken, $i);
        if($i + 1 < strlen($string) && $string[$i + 1] == ':') // Peek one ahead. Duplicate T_FIELD_NAME token if a T_FIELD_VALUE follows.
            $currentToken = $fieldToken;
    }

    /**
     * Parses a field entry
     *
     * @param array $tokens
     * @param Token $currentToken
     * @param string $string
     * @param int $i
     * @throws ParseException
     */
    static private function tokenizeField(&$tokens, &$currentToken, $string, $i)
    {
        if($currentToken->getData() == null)
            throw new ParseException('Expected T_FIELD_NAME, got nothing', $string, $i);
        if(!$currentToken->isTypeNoneOr(Token::T_FIELD_NAME))
            throw new ParseException('Expected T_FIELD_NAME, got ' . Token::getName($currentToken->getType()), $string, $i);
        $currentToken->setType(Token::T_FIELD_NAME);
        self::push($tokens, $currentToken, $i);
        $currentToken->setType(Token::T_FIELD_VALUE);
    }

    /**
     * Parses a search field
     * @param Token $currentToken
     * @param string $string
     * @param int $i
     * @throws ParseException
     */
    static private function tokenizeSearchField($currentToken, $string, $i)
    {
        if($currentToken->getData() != null)
            throw new ParseException('Expected nothing, got ' . Token::getName($currentToken->getType()), $string, $i);
        $currentToken->setStartPosition($i);
        $currentToken->setType(Token::T_FIELD_SEARCH);
    }

    /**
     * Puts the current token on the stack and creates a new one.
     *
     * Reuses the token if it contains no data.
     * Sets it's type to {@link Token::T_STRING} if it is {@link Token::T_NONE}.
     * Put the current token on the stack.
     * Create a new {@link Token::T_NONE} token and set it as current.
     *
     * @param array $tokens
     * @param \vierbergenlars\Forage\QueryParser\Token $currentToken
     * @param int $i The current position in the string being tokenized
     */
    static private function push(&$tokens, &$currentToken, $i)
    {
        if($currentToken->getData() === null) {
            $currentToken->setStartPosition($i);
            return;
        }
        $currentToken->setTypeIfNone(Token::T_STRING);
        $tokens[] = $currentToken;
        $currentToken = new Token(Token::T_NONE, $i);
    }

    /**
     * Reads an encapsulated (quoted) string
     * @param \vierbergenlars\Forage\QueryParser\Token $currentToken
     * @param string $string The string being tokenized
     * @param int $i The current position in the string being tokenized
     */
    static private function readEncString(Token $currentToken, $string, &$i)
    {
        while(++$i < strlen($string)) {
            if($string[$i] == '\\') {
                $currentToken->addData($string[++$i]);
            } else if($string[$i] != '"') {
                $currentToken->addData($string[$i]);
            } else {
                break;
            }
        }
    }

    /**
     * Reads an integer
     * @param \vierbergenlars\Forage\QueryParser\Token $currentToken
     * @param string $string The string being tokenized
     * @param int $i The current position in the string being tokenized
     */
    static private function readInt(Token $currentToken, $string, &$i)
    {
        while(++$i < strlen($string)) {
            if(in_array($string[$i], array('0', '1', '2', '3', '4', '5', '6', '7',
                        '8', '9', '-'), true)) {
                $currentToken->addData($string[$i]);
            } else {
                $i--;
                break;
            }
        }
    }

}
