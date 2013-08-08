<?php

namespace vierbergenlars\Norch\QueryParser;

use vierbergenlars\Norch\SearchQuery\QueryBuilder;
use vierbergenlars\Norch\QueryParser\Token;

class Compiler extends QueryBuilder
{

    public function updateQuery($queryExpr)
    {
        $tokens = Lexer::tokenize($queryExpr);
        $searchQuery = '';
        $currentField = null;
        foreach($tokens as $token) {
            switch($token->getType()) {
                case Token::T_STRING:
                    $searchQuery.= ' ' . $token->getData();
                    $currentField = null;
                    break;
                case Token::T_FIELD_NAME:
                    $currentField = $token->getData();
                    break;
                case Token::T_FIELD_WEIGHT:
                    if($currentField === null)
                        throw new ParseException('Unexpected T_FIELD_WEIGHT', $queryExpr, $token->getStartPosition());
                    $this->addWeight($currentField, $token->getData());
                    break;
                case Token::T_FIELD_VALUE:
                    if($currentField === null)
                        throw new ParseException('Unexpected T_FIELD_VALUE', $queryExpr, $token->getStartPosition());
                    $this->addFilter($currentField, $token->getData());
                    $currentField = null;
                    break;
                case Token::T_NONE: // This token should never occur
                    throw new ParseException('Unexpected T_NONE (This is a lexer bug, please report it)', $queryExpr, $token->getStartPostition());
                default:
                    throw new ParseException('Unknown token (This is a lexer bug, please report it)', $queryExpr, $token->getStartPosition());
            }
        }
        $this->setSearchQuery($searchQuery);
        return $this;
    }

}
