<?php

namespace vierbergenlars\Forage\ODM\HydrationSettings;

/**
 * A simple hydration strategy that injects all parameters as-is in the object.
 */
class SingleObjectHydration extends DeferHydration
{
    /**
     * The class name of the object that will be injected
     * @var string
     */
    protected $className;

    /**
     * Creates a new simple hydration strategy
     * @param string $className The class that will be hydrated (should implement {@link vierbergenlars\Forage\ODM\Indexable})
     * @throws \LogicException
     */
    public function __construct($className)
    {
        $interfaces = class_implements($className);
        if (!isset($interfaces['vierbergenlars\Forage\ODM\Indexable']))
            throw new \LogicException($className . ' should implement interface vierbergenlars\Forage\ODM\Indexable');
        $this->className = $className;
    }

    /**
     * {@inheritDoc}
     * @internal
     */
    protected function getClass($id, array $document)
    {
        return $this->className;
    }

    /**
     * {@inheritDoc}
     * @internal
     */
    protected function getParameters($id, array $document)
    {
        return array_merge($document, array('id'=>$id));
    }

    /**
     * Gets the document array from the object
     * @param \vierbergenlars\Forage\ODM\Indexable $document
     */
    private function getDocArray($document)
    {
        if(!is_a($document, $this->className))
            throw new \LogicException('Document should be of type ' . $this->className . ', got ' . get_class($document));
        return $document->toDocument();
    }

    /**
     * {@InheritDoc}
     * @param \vierbergenlars\Forage\ODM\Indexable $document
     * @internal
     */
    public function getDocument($document)
    {
        $doc = $this->getDocArray($document);
        unset($doc['id']);
        return $doc;
    }

    /**
     * {@InheritDoc}
     * @param \vierbergenlars\Forage\ODM\Indexable $document
     * @internal
     */
    public function getDocumentId($document)
    {
        $doc = $this->getDocArray($document);
        return $doc['id'];
    }

}
