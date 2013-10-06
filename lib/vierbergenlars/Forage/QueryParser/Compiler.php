<?php

namespace vierbergenlars\Forage\QueryParser;

use vierbergenlars\Forage\SearchQuery\QueryBuilder;
use vierbergenlars\Forage\QueryParser\Token;

/**
 * Compiles a string query to an executable query.
 */
class Compiler
{

    /**
     * The query builder the query gets inserted to
     * @var \vierbergenlars\Forage\SearchQuery\QueryBuilder
     */
    protected $queryBuilder;

    /**
     * List of fields that are allowed with T_FIELD_SEARCH
     * @var array
     */
    protected $allowedSearchFields = array();

    /**
     * List of fields that are allowed with T_FIELD_NAME
     * @var array
     */
    protected $allowedFieldNames = array();

    /**
     * List of tokens that are allowed
     * @var array
     */
    protected $allowedTokens = array();

    /**
     * Create a new compiler
     * @param \vierbergenlars\Forage\SearchQuery\QueryBuilder $queryBuilder The query builder to compile the query on
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Sets the allowed field names
     * @param array $fieldNames
     * @return \vierbergenlars\Forage\QueryParser\Compiler
     */
    public function setAllowedFieldNames(array $fieldNames)
    {
        $this->allowedFieldNames = $fieldNames;
        return $this;
    }

    /**
     * Checks if a field name is allowed
     * @param string $field
     * @return bool
     */
    protected function isAllowedFieldName($field)
    {
        return empty($this->allowedFieldNames) || in_array($field, $this->allowedFieldNames, true);
    }

    /**
     * Sets the allowed search fields
     * @param array $fieldNames
     * @return \vierbergenlars\Forage\QueryParser\Compiler
     */
    public function setAllowedSearchFields(array $fieldNames)
    {
        $this->allowedSearchFields = $fieldNames;
        return $this;
    }

    /**
     * Checks if a search field is allowed
     * @param string $field
     * @return bool
     */
    protected function isAllowedSearchField($field)
    {
        return empty($this->allowedSearchFields) || in_array($field, $this->allowedSearchFields, true);
    }

    /**
     * Sets the allowed tokens
     * @param array $tokens
     * @return \vierbergenlars\Forage\QueryParser\Compiler
     */
    public function setAllowedTokens(array $tokens)
    {
        $this->allowedTokens = $tokens;
        return $this;
    }

    /**
     * Checks if a token is allowed
     * @param \vierbergenlars\Forage\QueryParser\Token $token
     * @return bool
     */
    protected function isAllowedToken(Token $token)
    {
        return empty($this->allowedTokens) || in_array($token->getType(), $this->allowedTokens, true);
    }

    /**
     * Compiles the search query
     * @param string $queryExpr
     * @return \vierbergenlars\Forage\QueryParser\Compiler
     * @throws ParseException
     */
    public function compileQuery($queryExpr)
    {
        $tokens = Lexer::tokenize($queryExpr);
        $searchQuery = '';
        while(false !== ($token = current($tokens))) {
            if(!$this->isAllowedToken($token))
                throw new ParseException(Token::getName($token->getType()) . ' is disabled', $queryExpr, $token->getStartPosition());
            switch($token->getType()) {
                case Token::T_STRING:
                    $searchQuery.= ' ' . $token->getData();
                    break;
                case Token::T_FIELD_NAME:
                    $this->compileFieldName($tokens, $queryExpr);
                    break;
                case Token::T_FIELD_SEARCH:
                    $this->compileSearchField($tokens, $queryExpr);
                    break;
                default:
                    throw new ParseException('Unexpected ' . Token::getName($token->getType()) . ' (This is a lexer bug, please report it)', $queryExpr, $token->getStartPosition());
            }
            next($tokens);
        }
        $this->queryBuilder->setSearchQuery(substr($searchQuery, 1));
        return $this;
    }

    private function compileFieldName(&$tokens, $queryExpr)
    {
        $token = current($tokens);
        if(!$this->isAllowedFieldName($token->getData()))
            throw new ParseException('Field name not allowed', $queryExpr, $token->getStartPosition());
        $nextToken = next($tokens);
        if($nextToken === false)
            throw new ParseException('Unexpected end of token stream', $queryExpr, strlen($queryExpr));
        if(!$this->isAllowedToken($nextToken))
            throw new ParseException(Token::getName($nextToken->getType()) . ' is disabled', $queryExpr, $nextToken->getStartPosition());
        switch($nextToken->getType()) {
            case Token::T_FIELD_VALUE:
                $this->queryBuilder->addFilter($token->getData(), $nextToken->getData());
                break;
            case Token::T_FIELD_WEIGHT:
                $this->queryBuilder->addWeight($token->getData(), $nextToken->getData());
                break;
            default:
                throw new ParseException('Unexpected ' . Token::getName($nextToken->getType()), $queryExpr, $nextToken->getStartPosition());
        }
    }

    private function compileSearchField($tokens, $queryExpr)
    {
        $token = current($tokens);
        if(!$this->isAllowedSearchField($token->getData()))
            throw new ParseException('Search field not allowed', $queryExpr, $token->getStartPosition());
        $this->queryBuilder->addSearchField($token->getData());
    }

    /**
     * Gets the query builder
     * @return \vierbergenlars\Forage\SearchQuery\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * Gets the query
     * @return vierbergenlars\Forage\SearchQuery\Query
     */
    public function getQuery()
    {
        return $this->queryBuilder->getQuery();
    }

    public function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }

}
