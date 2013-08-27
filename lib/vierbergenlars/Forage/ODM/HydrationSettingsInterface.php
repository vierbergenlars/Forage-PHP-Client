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
     * Gets the object from a document
     * @param array $document
     * @return object
     */
    public function getObject(array $document);
}
