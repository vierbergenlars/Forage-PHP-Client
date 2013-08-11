<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchResult\Hit;
use vierbergenlars\Norch\ODM\HydrationSettingsInterface;

/**
 * A hydrateable search hit
 */
class SearchHit extends Hit
{
    /**
     * Hydrates the document into an object
     * @internal
     * @param \vierbergenlars\Norch\ODM\HydrationSettingsInterface $hydrationSettings
     */
    public function hydrate(HydrationSettingsInterface $hydrationSettings)
    {
        $this->document = $hydrationSettings->getObject($this->document);
    }
}
