<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchQuery\Query;
use vierbergenlars\Norch\Transport\TransportInterface;

/**
 * A search query that automatically converts all search results to an hydrated object
 */
class SearchQuery extends Query
{

    protected $searchResultClass = '\vierbergenlars\Norch\ODM\SearchResult';

    /**
     * Settings for object hydration
     * @var \vierbergenlars\Norch\ODM\HydrationSettingsInterface
     */
    protected $hydrationSettings;

    /**
     * Creates a new search query
     *
     * @internal
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport
     * @param \vierbergenlars\Norch\ODM\HydrationSettingsInterface $hydrationSettings
     * @param string $query
     */
    public function __construct(TransportInterface $transport,
                                HydrationSettingsInterface $hydrationSettings,
                                $query = '')
    {
        $this->hydrationSettings = $hydrationSettings;
        parent::__construct($transport, $query);
    }

    /**
     * Executes the search and hydrates the result
     *
     * @return \vierbergenlars\Norch\ODM\SearchResult
     */
    public function execute() {
        $searchResult = parent::execute();
        $searchResult->hydrate($this->hydrationSettings);
        return $searchResult;
    }
}
