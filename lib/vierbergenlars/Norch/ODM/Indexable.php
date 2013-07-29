<?php

namespace vierbergenlars\Norch\ODM;

use Defer\Deferrable;

/**
 * Should be implemented by all objects that are indexable
 */
interface Indexable extends Deferrable
{
    /**
     * Gets the id of the document.
     * @return string|int An unique identifier of the document
     */
    public function getId();

    /**
     * Converts the object to a document.
     * @return array The returned array should contain a mapping from fields to their values. It should not contain an id key.
     */
    public function toDocument();
}
