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
     * The name of the object to hydrate
     * @var string
     */
    protected $hydrateObject;

    /**
     * Creates a new document mapper
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport The transport to use
     * @param string $hydrateObject The name of the object to hydrate.
     *      The object should implement {@link \Defer\Deferrable}
     * @throws \LogicException
     */
    public function __construct(TransportInterface $transport, $hydrateObject) {
        $interfaces = class_implements($hydrateObject);
        if(!isset($interfaces['Defer\Deferrable']))
            throw new \LogicException($hydrateObject.' should implement interface \Defer\Deferrable');

        $this->hydrateObject = $hydrateObject;
        parent::__construct($transport);
    }

    /**
     * Creates a new query builder, with a search result that is automatically hydrated.
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function createQueryBuilder() {
        $query = new SearchQuery($this->transport, $this->hydrateObject);
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
