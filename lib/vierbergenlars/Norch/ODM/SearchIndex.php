<?php

namespace vierbergenlars\Norch\ODM;

use vierbergenlars\Norch\SearchIndex\Index;
use vierbergenlars\Norch\Transport\TransportInterface;

/**
 * A search index that can index object
 */
class SearchIndex extends Index
{

    /**
     * The hydration settings
     * @var \vierbergenlars\Norch\ODM\HydrationSettingsInterface
     */
    protected $hydrationSettings;

    /**
     * @internal
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport
     * @param \vierbergenlars\Norch\ODM\HydrationSettingsInterface $hydrationSettings
     */
    public function __construct(TransportInterface $transport,
                                HydrationSettingsInterface $hydrationSettings)
    {
        $this->hydrationSettings = $hydrationSettings;
        parent::__construct($transport);
    }

    /**
     * {@inheritDoc}
     *
     * @param object $document
     * @return \vierbergenlars\Norch\ODM\SearchIndex
     */
    public function addDocument($document)
    {
        parent::addDocument($this->hydrationSettings->getDocument($document));
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param object|string|int $document
     *      When the document to remove is given as a string or an integer,
     *      it is used as an id. If it is an object, {@link HydrationSettingsInterface::getDocument()}
     *      gets called, and the document id will be determined from the returned array.
     * @return \vierbergenlars\Norch\ODM\SearchIndex
     */
    public function removeDocument($document)
    {
        if(is_object($document)) {
            $doc = $this->hydrationSettings->getDocument($document);
            parent::removeDocument($doc['id']);
        } else {
            parent::removeDocument($document);
        }
        return $this;
    }

}
