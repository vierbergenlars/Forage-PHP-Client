<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchResult\Hit;
use Defer\Object as Defer;

/**
 * A hydrateable search hit
 */
class SearchHit extends Hit
{
    /**
     * Hydrates the document into an object
     * @param string $object The name of the object to hydrate to
     */
    public function hydrateObject($object)
    {
        array_walk($this->document, function(&$field) {
            if(is_array($field))
                $field = new \ArrayObject($field);
        });
        $this->document = Defer::defer($this->getDocument(), $object);
    }
}
