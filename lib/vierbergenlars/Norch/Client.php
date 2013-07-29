<?php

namespace vierbergenlars\Norch;

use vierbergenlars\Norch\Transport\TransportInterface;
use vierbergenlars\Norch\SearchQuery\TransportAwareQuery;
use vierbergenlars\Norch\SearchQuery\QueryBuilder;
use vierbergenlars\Norch\SearchIndex\TransportAwareIndex;
use vierbergenlars\Norch\ODM\DocumentMapper;

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
        $query = new TransportAwareQuery($this->transport);
        return new QueryBuilder($query);
    }

    /**
     * Gets the search index
     * @return \vierbergenlars\Norch\SearchIndex\TransportAwareIndex
     */
    public function getIndex()
    {
        return new TransportAwareIndex($this->transport);
    }

    /**
     * Creates a new document mapper
     * @param string $hydrateObject The object to map the results to
     * @return \vierbergenlars\Norch\ODM\DocumentMapper
     */
    public function createDocumentMapper($hydrateObject)
    {
        return new DocumentMapper($this->transport, $hydrateObject);
    }
}
