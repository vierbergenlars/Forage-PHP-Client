<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\Client;
use vierbergenlars\Norch\ODM\SearchQuery;
use vierbergenlars\Norch\Transport\TransportInterface;
use vierbergenlars\Norch\SearchQuery\QueryBuilder;

/**
 * The document mapper
 */
class DocumentMapper extends Client
{
    /**
     * Settings for parameter injection
     * @var \vierbergenlars\Norch\ODM\HydrationSettingsInterface
     */
    protected $hydrationSettings;

    /**
     * Creates a new document mapper
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport The transport to use
     * @param \vierbergenlars\Norch\ODM\HydrationSettingsInterface $hydrationSettings
     */
    public function __construct(TransportInterface $transport,
                                HydrationSettingsInterface $hydrationSettings)
    {
        $this->hydrationSettings = $hydrationSettings;
        parent::__construct($transport);
    }

    /**
     * Creates a new query builder, with a search result that is automatically hydrated.
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function createQueryBuilder() {
        $query = new SearchQuery($this->transport, $this->hydrationSettings);
        return new QueryBuilder($query);
    }

    /**
     * Gets the search index
     * @return \vierbergenlars\Norch\ODM\SearchIndex
     */
    public function getIndex() {
        return new SearchIndex($this->transport);
    }
}
