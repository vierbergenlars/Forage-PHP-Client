<?php

namespace vierbergenlars\Forage\ODM\HydrationSettings;

use vierbergenlars\Forage\ODM\HydrationSettingsInterface;
use Defer\Object as Defer;
use vierbergenlars\Forage\ODM\SearchHit;

/**
 * Base class for hydration strategies that use vierbergenlars/defer to hydrate their objects
 */
abstract class DeferHydration implements HydrationSettingsInterface
{

    /**
     * Gets the properties to be injected in the object
     * @param string|int $id The id of the document received from the search query
     * @param array $document The document received from the search query
     * @return array A map of object properties to values
     */
    abstract protected function getParameters($id, array $document);

    /**
     * Gets the class name of the object that will be hydrated
     * @param string|int $id The id of the document received from the search query
     * @param array $document The document received from the search query
     * @return string A fully qualified class name. (Should implement {@link Defer\Deferrable})
     */
    abstract protected function getClass($id, array $document);

    /**
     * Gets the object from a document
     * @param array $document
     * @return object
     */
    public function getObject(SearchHit $hit)
    {
        $document = $hit->getDocument();
        array_walk($document, function(&$field) {
            if(is_array($field))
                $field = new \ArrayObject($field);
        });
        $id = $hit->getId();
        $data = $this->getParameters($id, $document);
        $class = $this->getClass($id, $document);
        return Defer::defer($data, $class);
    }

}
