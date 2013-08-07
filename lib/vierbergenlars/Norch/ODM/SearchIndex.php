<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchIndex\Index;

/**
 * A search index that can index object
 */
class SearchIndex extends Index
{

    /**
     * {@inheritDoc}
     *
     * @param \vierbergenlars\Norch\ODM\Indexable $document
     * @return \vierbergenlars\Norch\ODM\SearchIndex
     */
    public function addDocument($document)
    {
        if(!($document instanceof Indexable))
            throw new \BadMethodCallException('Parameter 1 of ' . __METHOD__ . ' should be Indexable, ' . (is_object($document) ? get_class($document) : gettype($document)) . ' given.');
        parent::addDocument($document->toDocument());
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param \vierbergenlars\Norch\ODM\Indexable|string|int $document
     *      When the document to remove is given as a string or an integer,
     *      it is used as an id. If it is an {@link Indexable}, {@link Indexable::toDocument()}
     *      gets called, and the document id will be determined from the returned array.
     * @return \vierbergenlars\Norch\ODM\SearchIndex
     */
    public function removeDocument($document)
    {
        if($document instanceof Indexable) {
            $doc = $document->toDocument();
            parent::removeDocument($doc['id']);
        } else {
            parent::removeDocument($document);
        }
        return $this;
    }

}
