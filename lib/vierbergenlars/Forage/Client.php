<?php

namespace vierbergenlars\Forage;

use vierbergenlars\Forage\Transport\TransportInterface;
use vierbergenlars\Forage\SearchQuery\Query;
use vierbergenlars\Forage\SearchQuery\QueryBuilder;
use vierbergenlars\Forage\SearchIndex\Index;
use vierbergenlars\Forage\ODM\DocumentMapper;
use vierbergenlars\Forage\ODM\HydrationSettingsInterface;

class Client
{
    /**
     * The transport to use
     * @var \vierbergenlars\Forage\Transport\TransportInterface
     */
    protected $transport;

    /**
     * Creates a new Forage client
     * @param \vierbergenlars\Forage\Transport\TransportInterface $transport
     */
    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Creates a new query builder
     * @return \vierbergenlars\Forage\SearchQuery\QueryBuilder
     */
    public function createQueryBuilder()
    {
        $query = new Query($this->transport);
        return new QueryBuilder($query);
    }

    /**
     * Gets the search index
     * @return \vierbergenlars\Forage\SearchIndex\Index
     */
    public function getIndex()
    {
        return new Index($this->transport);
    }

    /**
     * Creates a new document mapper
     * @param \vierbergenlars\Forage\ODM\HydrationSettingsInterface $hydrationSettings
     * @return \vierbergenlars\Forage\ODM\DocumentMapper
     */
    public function createDocumentMapper(HydrationSettingsInterface $hydrationSettings)
    {
        return new DocumentMapper($this->transport, $hydrationSettings);
    }
}
