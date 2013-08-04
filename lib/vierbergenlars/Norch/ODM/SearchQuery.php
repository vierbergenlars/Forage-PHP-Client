<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchQuery\TransportAwareQuery;
use vierbergenlars\Norch\Transport\TransportInterface;

/**
 * A search query that automatically converts all search results to an hydrated object
 */
class SearchQuery extends TransportAwareQuery
{

    protected $searchResultClass = '\vierbergenlars\Norch\ODM\SearchResult';

    /**
     * The object to hydrate
     * @var string
     */
    protected $hydrateObject;

    /**
     * Creates a new search query
     *
     * @internal
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport
     * @param string $hydrateObject The name of the object to hydrate, and should implement \Defer\Deferrable
     * @param string $query
     */
    public function __construct(TransportInterface $transport, $hydrateObject, $query = '')
    {
        $this->hydrateObject = $hydrateObject;
        parent::__construct($transport, $query);
    }

    /**
     * Executes the search and hydrates the result
     *
     * @return \vierbergenlars\Norch\ODM\SearchResult
     */
    public function execute() {
        $searchResult = parent::execute();
        $searchResult->hydrateObject($this->hydrateObject);
        return $searchResult;
    }
}