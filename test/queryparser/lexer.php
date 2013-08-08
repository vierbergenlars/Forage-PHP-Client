<?php

namespace test\queryparser;

use vierbergenlars\Norch\QueryParser\Lexer as L;
use vierbergenlars\Norch\QueryParser\Token;
use vierbergenlars\Norch\QueryParser\ParseException;

class lexer extends \UnitTestCase
{

    function testLexerTokenize()
    {
        $ex = false;
        try {
            L::tokenize('blabal"search query"field: "long value"');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $ex = false;
        try {
            L::tokenize('blabal "sea"rch query"field: "long value"');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $ex = false;
        try {
            L::tokenize('blabal "search query"field: "long valu"e"');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $ex = false;
        try {
            L::tokenize('blabal "search query" "field: "long value"');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $ex = false;
        try {
            L::tokenize('field^25: long ^2 value field2^-1 sfi');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $tokens = L::tokenize('blabal "search query" "field": "long value"');
        $this->assertEqual($tokens[0]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[0]->getData(), 'blabal');
        $this->assertEqual($tokens[1]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[1]->getData(), 'search query');
        $this->assertEqual($tokens[2]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[2]->getData(), 'field');
        $this->assertEqual($tokens[3]->getType(), Token::T_FIELD_VALUE);
        $this->assertEqual($tokens[3]->getData(), 'long value');

        $tokens = L::tokenize('blabal "search query" "field:" "long value"');
        $this->assertEqual($tokens[0]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[0]->getData(), 'blabal');
        $this->assertEqual($tokens[1]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[1]->getData(), 'search query');
        $this->assertEqual($tokens[2]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[2]->getData(), 'field:');
        $this->assertEqual($tokens[3]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[3]->getData(), 'long value');


        $tokens = L::tokenize('blabal "search query" field: "long value"');
        $this->assertEqual($tokens[0]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[0]->getData(), 'blabal');
        $this->assertEqual($tokens[1]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[1]->getData(), 'search query');
        $this->assertEqual($tokens[2]->getType(), Token::T_FIELD_NAME);
        $this->assertEqual($tokens[2]->getData(), 'field');
        $this->assertEqual($tokens[3]->getType(), Token::T_FIELD_VALUE);
        $this->assertEqual($tokens[3]->getData(), 'long value');

        $tokens = L::tokenize('field^25: long value field2^-1 sfi');
        $this->assertEqual($tokens[0]->getType(), Token::T_FIELD_NAME);
        $this->assertEqual($tokens[0]->getData(), 'field');
        $this->assertEqual($tokens[1]->getType(), Token::T_FIELD_WEIGHT);
        $this->assertEqual($tokens[1]->getData(), '25');
        $this->assertEqual($tokens[2]->getType(), Token::T_FIELD_VALUE);
        $this->assertEqual($tokens[2]->getData(), 'long');
        $this->assertEqual($tokens[3]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[3]->getData(), 'value');
        $this->assertEqual($tokens[4]->getType(), Token::T_FIELD_NAME);
        $this->assertEqual($tokens[4]->getData(), 'field2');
        $this->assertEqual($tokens[5]->getType(), Token::T_FIELD_WEIGHT);
        $this->assertEqual($tokens[5]->getData(), '-1');
        $this->assertEqual($tokens[6]->getType(), Token::T_STRING);
        $this->assertEqual($tokens[6]->getData(), 'sfi');
    }

}
