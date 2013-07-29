<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchResult\SearchResult as Result;

/**
 * A hydrateable search result
 */
class SearchResult extends Result
{
    protected $hitClass = '\vierbergenlars\Norch\ODM\SearchHit';

    /**
     * Hydrates all results into objects
     * @param string $object The name of the object to hydrate into
     */
    public function hydrateObject($object)
    {
        foreach($this as $hit)
            $hit->hydrateObject($object);
    }
}
