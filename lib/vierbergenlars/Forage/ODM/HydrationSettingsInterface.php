<?php

namespace vierbergenlars\Forage\ODM;

/**
 * Interface for settings for the hydration of search results in objects
 */
interface HydrationSettingsInterface
{
    /**
     * Gets the document from an object
     * @param object $document
     * @return array The document to be stored in the database
     */
    public function getDocument($document);

    /**
     * Gets the id of the document from the object
     * @param object $document
     * @return string|int The id of the document to be stored in the database
     */
    public function getDocumentId($document);

    /**
     * Gets the object from a document
     * @param array $document
     * @return SearchHit
     */
    public function getObject(SearchHit $document);
}
