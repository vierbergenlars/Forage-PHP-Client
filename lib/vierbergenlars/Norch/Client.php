<?php

namespace vierbergenlars\Norch;

use vierbergenlars\Norch\Transport\TransportInterface;
use vierbergenlars\Norch\SearchQuery\TransportAwareQuery;
use vierbergenlars\Norch\SearchQuery\QueryBuilder;
use vierbergenlars\Norch\SearchIndex\TransportAwareIndex;

class Client
{
    /**
     * The transport to use
     * @var \vierbergenlars\Norch\Transport\TransportInterface
     */
    private $transport;

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
}
