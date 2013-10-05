<?php

namespace vierbergenlars\Forage\ODM;

use vierbergenlars\Forage\SearchResult\Hit;
use vierbergenlars\Forage\ODM\HydrationSettingsInterface;

/**
 * A hydrateable search hit
 */
class SearchHit extends Hit
{
    /**
     * Hydrates the document into an object
     * @internal
     * @param \vierbergenlars\Forage\ODM\HydrationSettingsInterface $hydrationSettings
     */
    public function hydrate(HydrationSettingsInterface $hydrationSettings)
    {
        $this->document = $hydrationSettings->getObject($this);
    }
}
