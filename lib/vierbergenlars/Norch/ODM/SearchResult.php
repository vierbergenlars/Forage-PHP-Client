<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchResult\SearchResult as Result;
use vierbergenlars\Norch\ODM\HydrationSettingsInterface;

/**
 * A hydrateable search result
 */
class SearchResult extends Result
{
    protected $hitClass = '\vierbergenlars\Norch\ODM\SearchHit';

    /**
     * Hydrates all results into objects
     * @internal
     * @param \vierbergenlars\Norch\ODM\HydrationSettingsInterface $hydrationSettings
     */
    public function hydrate(HydrationSettingsInterface $hydrationSettings)
    {
        foreach($this as $hit)
            $hit->hydrate($hydrationSettings);
    }
}
