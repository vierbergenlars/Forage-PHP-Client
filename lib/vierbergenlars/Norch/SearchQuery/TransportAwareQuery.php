<?php

namespace vierbergenlars\Norch\SearchQuery;

use vierbergenlars\Norch\Transport\TransportInterface;

/**
 * A search query that already contains a transport
 */
class TransportAwareQuery extends Query
{
    /**
     * The transport to use
     * @var \vierbergenlars\Norch\Transport\TransportInterface
     */
    private $transport;

    /**
     * Creates a new query with a transport
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport
     * @param string $query
     */
    public function __construct(TransportInterface $transport, $query = '') {
        $this->transport = $transport;
        parent::__construct($query);
    }

    /**
     * Executes the query
     * @return \vierbergenlars\Norch\SearchResult\SearchResult
     */
    public function execute()
    {
        return parent::execute($this->transport);
    }
}
