<?php

namespace vierbergenlars\Norch\ODM\HydrationSettings;

use vierbergenlars\Norch\ODM\HydrationSettingsInterface;
use Defer\Object as Defer;

/**
 * Base class for hydration strategies that use vierbergenlars/defer to hydrate their objects
 */
abstract class DeferHydration implements HydrationSettingsInterface
{

    /**
     * Gets the properties to be injected in the object
     * @param array $document The document received from the search query
     * @return array A map of object properties to values
     */
    abstract public function getParameters(array $document);

    /**
     * Gets the class name of the object that will be hydrated
     * @param array $document The document received from the search query
     * @return string A fully qualified class name. (Should implement {@link Defer\Deferrable})
     */
    abstract public function getClass(array $document);

    /**
     * Gets the object from a document
     * @param array $document
     * @return object
     */
    public function getObject(array $document)
    {
        array_walk($document, function(&$field) {
            if(is_array($field))
                $field = new \ArrayObject($field);
        });
        $data = $this->getParameters($document);
        $class = $this->getClass($document);
        return Defer::defer($data, $class);
    }

}
