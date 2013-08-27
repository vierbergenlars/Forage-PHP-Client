<?php

namespace vierbergenlars\Forage\ODM;

use Defer\Deferrable;

/**
 * Should be implemented by all objects that are indexable
 */
interface Indexable extends Deferrable
{
    /**
     * Converts the object to a document.
     * @return array The returned array should contain a mapping from fields to their values. It should also contain an id key.
     */
    public function toDocument();
}
