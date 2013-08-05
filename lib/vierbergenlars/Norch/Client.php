<?php

namespace vierbergenlars\Norch;

use vierbergenlars\Norch\Transport\TransportInterface;
use vierbergenlars\Norch\SearchQuery\Query;
use vierbergenlars\Norch\SearchQuery\QueryBuilder;
use vierbergenlars\Norch\SearchIndex\Index;
use vierbergenlars\Norch\ODM\DocumentMapper;
use vierbergenlars\Norch\ODM\HydrationSettingsInterface;

class Client
{
    /**
     * The transport to use
     * @var \vierbergenlars\Norch\Transport\TransportInterface
     */
    protected $transport;

    /**
     * Creates a new Norch client
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport
     */
    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Creates a new query builder
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function createQueryBuilder()
    {
        $query = new Query($this->transport);
        return new QueryBuilder($query);
    }

    /**
     * Gets the search index
     * @return \vierbergenlars\Norch\SearchIndex\TransportAwareIndex
     */
    public function getIndex()
    {
        return new Index($this->transport);
    }

    /**
     * Creates a new document mapper
     * @param \vierbergenlars\Norch\ODM\HydrationSettingsInterface $hydrationSettings
     * @return \vierbergenlars\Norch\ODM\DocumentMapper
     */
    public function createDocumentMapper(HydrationSettingsInterface $hydrationSettings)
    {
        return new DocumentMapper($this->transport, $hydrationSettings);
    }
}
