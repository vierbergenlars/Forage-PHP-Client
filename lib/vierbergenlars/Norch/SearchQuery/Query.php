<?php

namespace vierbergenlars\Norch\SearchQuery;

use vierbergenlars\Norch\Transport\TransportInterface;

/**
 * A search query
 */
class Query
{
    /**
     * The search query
     * @var string
     */
    public $query = '';

    /**
     * The fields to search in
     * @var array
     */
    public $searchFields = array();

    /**
     * The fields to facet on
     * @var array
     */
    public $facetFields = array();

    /**
     * The fields to filter the search result on
     * @var array
     */
    public $searchFilters = array();

    /**
     * The offset to the start of the result list
     * @var int
     */
    public $offset = 0;

    /**
     * The number of records to fetch
     * @var int
     */
    public $limit = 10;

    /**
     * The weights of each column
     * @var array
     */
    public $weights = array();

    /**
     * The class that parses the results
     * @var string
     */
    protected $searchResultClass = '\vierbergenlars\Norch\SearchResult\SearchResult';

    /**
     * Creates a new search query
     *
     * @param string $query
     */
    public function __construct($query = '') {
        $this->query = $query;
    }

    /**
     * Sets the search query
     *
     * @param string $query
     * @return \vierbergenlars\Norch\SearchQuery\Query
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Sets the fields to search in
     *
     * @param array $searchFields
     * @return \vierbergenlars\Norch\SearchQuery\Query
     */
    public function setSearchFields(array $searchFields)
    {
        $this->searchFields = $searchFields;
        return $this;
    }

    /**
     * Sets the fields to facet on
     *
     * @param array $facetFields
     * @return \vierbergenlars\Norch\SearchQuery\Query
     */
    public function setFacetFields(array $facetFields)
    {
        $this->facetFields = $facetFields;
        return $this;
    }

    /**
     * Sets filters for search fields
     * @param array $searchFilters
     * @return \vierbergenlars\Norch\SearchQuery\Query
     */
    public function setSearchFilters(array $searchFilters)
    {
        $this->searchFilters = $searchFilters;
        return $this;
    }

    /**
     * Sets the offset to the start of the result list
     * @param int $offset
     * @return \vierbergenlars\Norch\SearchQuery\Query
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Sets the number of records to fetch
     * @param int $limit
     * @return \vierbergenlars\Norch\SearchQuery\Query
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Sets the weights of each column
     * @param array $weights
     * @return \vierbergenlars\Norch\SearchQuery\Query
     */
    public function setWeights(array $weights)
    {
        $this->weights = $weights;
        return $this;
    }

    /**
     * Executes the query
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport
     * @return \vierbergenlars\Norch\SearchResult\SearchResult
     */
    public function execute(TransportInterface $transport)
    {
        $result = $transport->search(
            $this->query,
            $this->searchFields,
            $this->facetFields,
            $this->searchFilters,
            $this->offset,
            $this->limit,
            $this->weights
        );

        $resultClass = $this->searchResultClass;

        return new $resultClass($result);
    }
}
