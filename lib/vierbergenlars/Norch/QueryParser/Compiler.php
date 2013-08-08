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
        while(false !== ($token = current($tokens))) {
            switch($token->getType()) {
                case Token::T_STRING:
                    $searchQuery.= ' ' . $token->getData();
                    break;
                case Token::T_FIELD_NAME:
                    $nextToken = next($tokens);
                    if($nextToken === false)
                        throw new ParseException('Unexpected end of token stream');
                    switch($nextToken->getType()) {
                        case Token::T_FIELD_VALUE:
                            $this->addFilter($token->getData(), $nextToken->getData());
                            break;
                        case Token::T_FIELD_WEIGHT:
                            $this->addWeight($token->getData(), $nextToken->getData());
                            break;
                        default:
                            throw new ParseException('Unexpected ' . Token::getName($nextToken->getType()), $queryExpr, $token->getStartPosition());
                    }
                    break;
                case Token::T_NONE: // This token should never occur
                    throw new ParseException('Unexpected T_NONE (This is a lexer bug, please report it)', $queryExpr, $token->getStartPostition());
                default:
                    throw new ParseException('Unknown token (This is a lexer bug, please report it)', $queryExpr, $token->getStartPosition());
            }
            next($tokens);
        }
        $this->setSearchQuery($searchQuery);
        return $this;
    }

}
