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
                        throw new ParseException('Unexpected end of token stream', $queryExpr, strlen($queryExpr));
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
                case Token::T_FIELD_SEARCH:
                    $this->addSearchField($token->getData());
                    break;
                default:
                    throw new ParseException('Unexpected ' . Token::getName($token->getType()) . ' (This is a lexer bug, please report it)', $queryExpr, $token->getStartPosition());
            }
            next($tokens);
        }
        $this->setSearchQuery(substr($searchQuery, 1));
        return $this;
    }

}
