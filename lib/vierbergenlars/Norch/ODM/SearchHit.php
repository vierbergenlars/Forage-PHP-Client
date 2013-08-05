<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchResult\Hit;
use vierbergenlars\Norch\ODM\HydrationSettingsInterface;
use Defer\Object as Defer;

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
        array_walk($this->document, function(&$field) {
            if(is_array($field))
                $field = new \ArrayObject($field);
        });
        $document = $hydrationSettings->getParameters($this->document);
        $class = $hydrationSettings->getClass($this->document);
        $this->document = Defer::defer($document, $class);
    }
}
