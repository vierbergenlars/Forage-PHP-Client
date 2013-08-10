<?php

namespace vierbergenlars\Norch\QueryParser;

use vierbergenlars\Norch\SearchQuery\QueryBuilder;
use vierbergenlars\Norch\QueryParser\Token;

/**
 * Compiles a string query to an executable query.
 */
class Compiler
{

    /**
     * The query builder the query gets inserted to
     * @var \vierbergenlars\Norch\SearchQuery\QueryBuilder
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
     * @param \vierbergenlars\Norch\SearchQuery\QueryBuilder $queryBuilder The query builder to compile the query on
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Sets the allowed field names
     * @param array $fieldNames
     * @return \vierbergenlars\Norch\QueryParser\Compiler
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
     * @return \vierbergenlars\Norch\QueryParser\Compiler
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
     * @return \vierbergenlars\Norch\QueryParser\Compiler
     */
    public function setAllowedTokens(array $tokens)
    {
        $this->allowedTokens = $tokens;
        return $this;
    }

    /**
     * Checks if a token is allowed
     * @param \vierbergenlars\Norch\QueryParser\Token $token
     * @return bool
     */
    protected function isAllowedToken(Token $token)
    {
        return empty($this->allowedTokens) || in_array($token->getType(), $this->allowedTokens, true);
    }

    /**
     * Compiles the search query
     * @param string $queryExpr
     * @return \vierbergenlars\Norch\QueryParser\Compiler
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
                    if(!$this->isAllowedFieldName($token->getData()))
                        throw new ParseException('Field name not allowed', $queryExpr, $token->getStartPosition());
                    $nextToken = next($tokens);
                    if($nextToken === false)
                        throw new ParseException('Unexpected end of token stream', $queryExpr, strlen($queryExpr));
                    if(!$this->isAllowedToken($token))
                        throw new ParseException(Token::getName($token->getType()) . ' is disabled', $queryExpr, $token->getStartPosition());
                    switch($nextToken->getType()) {
                        case Token::T_FIELD_VALUE:
                            $this->queryBuilder->addFilter($token->getData(), $nextToken->getData());
                            break;
                        case Token::T_FIELD_WEIGHT:
                            $this->queryBuilder->addWeight($token->getData(), $nextToken->getData());
                            break;
                        default:
                            throw new ParseException('Unexpected ' . Token::getName($nextToken->getType()), $queryExpr, $token->getStartPosition());
                    }
                    break;
                case Token::T_FIELD_SEARCH:
                    if(!$this->isAllowedSearchField($token->getData()))
                        throw new ParseException('Search field not allowed', $queryExpr, $token->getStartPosition());
                    $this->queryBuilder->addSearchField($token->getData());
                    break;
                default:
                    throw new ParseException('Unexpected ' . Token::getName($token->getType()) . ' (This is a lexer bug, please report it)', $queryExpr, $token->getStartPosition());
            }
            next($tokens);
        }
        $this->queryBuilder->setSearchQuery(substr($searchQuery, 1));
        return $this;
    }

    /**
     * Gets the query builder
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * Gets the query
     * @return vierbergenlars\Norch\SearchQuery\Query
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
