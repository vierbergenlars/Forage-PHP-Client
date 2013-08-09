<?php

namespace vierbergenlars\Norch\ODM;

/**
 * Interface for settings for the hydration of search results in objects
 */
interface HydrationSettingsInterface
{

    /**
     * Gets the properties to be injected in the object
     * @param array $document The document received from the search query
     * @return array A map of object properties to values
     */
    public function getParameters(array $document);

    /**
     * Gets the class name of the object that will be hydrated
     * @param array $document The document received from the search query
     * @return string A fully qualified class name. (Should implement {@link Defer\Deferrable})
     */
    public function getClass(array $document);

    /**
     * Gets the document from an object
     * @param object $document
     * @return array The document to be stored in the database
     */
    public function getDocument($document);
}
