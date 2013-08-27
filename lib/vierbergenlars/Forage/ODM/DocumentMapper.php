<?php

namespace vierbergenlars\Forage\ODM;

use vierbergenlars\Forage\Client;
use vierbergenlars\Forage\ODM\SearchQuery;
use vierbergenlars\Forage\Transport\TransportInterface;
use vierbergenlars\Forage\SearchQuery\QueryBuilder;

/**
 * The document mapper
 */
class DocumentMapper extends Client
{
    /**
     * Settings for parameter injection
     * @var \vierbergenlars\Forage\ODM\HydrationSettingsInterface
     */
    protected $hydrationSettings;

    /**
     * Creates a new document mapper
     * @param \vierbergenlars\Forage\Transport\TransportInterface $transport The transport to use
     * @param \vierbergenlars\Forage\ODM\HydrationSettingsInterface $hydrationSettings
     */
    public function __construct(TransportInterface $transport,
                                HydrationSettingsInterface $hydrationSettings)
    {
        $this->hydrationSettings = $hydrationSettings;
        parent::__construct($transport);
    }

    /**
     * Creates a new query builder, with a search result that is automatically hydrated.
     * @return \vierbergenlars\Forage\SearchQuery\QueryBuilder
     */
    public function createQueryBuilder() {
        $query = new SearchQuery($this->transport, $this->hydrationSettings);
        return new QueryBuilder($query);
    }

    /**
     * Gets the search index
     * @return \vierbergenlars\Forage\ODM\SearchIndex
     */
    public function getIndex() {
        return new SearchIndex($this->transport, $this->hydrationSettings);
    }
}
