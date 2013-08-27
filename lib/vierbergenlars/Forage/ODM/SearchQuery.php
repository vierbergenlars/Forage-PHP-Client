<?php

namespace vierbergenlars\Forage\ODM;

use vierbergenlars\Forage\SearchQuery\Query;
use vierbergenlars\Forage\Transport\TransportInterface;

/**
 * A search query that automatically converts all search results to an hydrated object
 */
class SearchQuery extends Query
{

    protected $searchResultClass = '\vierbergenlars\Forage\ODM\SearchResult';

    /**
     * Settings for object hydration
     * @var \vierbergenlars\Forage\ODM\HydrationSettingsInterface
     */
    protected $hydrationSettings;

    /**
     * Creates a new search query
     *
     * @internal
     * @param \vierbergenlars\Forage\Transport\TransportInterface $transport
     * @param \vierbergenlars\Forage\ODM\HydrationSettingsInterface $hydrationSettings
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
     * @return \vierbergenlars\Forage\ODM\SearchResult
     */
    public function execute() {
        $searchResult = parent::execute();
        $searchResult->hydrate($this->hydrationSettings);
        return $searchResult;
    }
}
