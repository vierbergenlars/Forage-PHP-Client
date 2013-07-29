<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchIndex\TransportAwareIndex;

/**
 * A search index that can index object
 */
class SearchIndex extends TransportAwareIndex
{
    /**
     * {@inheritDoc}
     *
     * @param \vierbergenlars\Norch\ODM\Indexable $document
     * @return \vierbergenlars\Norch\ODM\SearchIndex
     */
    public function addDocument(Indexable $document) {
        parent::addDocument($document->getId(), $document->toDocument());
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param \vierbergenlars\Norch\ODM\Indexable|string|int $document
     *      When the document to remove is given as a string or an integer,
     *      it is used as an id. If it is an {@link Indexable}, {@link Indexable::getId()}
     *      gets called to get the document id.
     * @return \vierbergenlars\Norch\ODM\SearchIndex
     */
    public function removeDocument($document) {
        if($document instanceof Indexable)
            parent::removeDocument ($document->getId());
        else
            parent::removeDocument($document);
        return $this;
    }
}
