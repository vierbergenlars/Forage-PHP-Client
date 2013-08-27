<?php

namespace vierbergenlars\Forage\ODM;

use vierbergenlars\Forage\SearchResult\SearchResult as Result;
use vierbergenlars\Forage\ODM\HydrationSettingsInterface;

/**
 * A hydrateable search result
 */
class SearchResult extends Result
{
    protected $hitClass = '\vierbergenlars\Forage\ODM\SearchHit';

    /**
     * Hydrates all results into objects
     * @internal
     * @param \vierbergenlars\Forage\ODM\HydrationSettingsInterface $hydrationSettings
     */
    public function hydrate(HydrationSettingsInterface $hydrationSettings)
    {
        foreach($this as $hit)
            $hit->hydrate($hydrationSettings);
    }
}
