<?php

namespace vierbergenlars\Norch\SearchIndex;

use vierbergenlars\Norch\Transport\TransportInterface;

/**
 * The search index
 */
class Index
{
    /**
     * The documents in the queue to be uploaded
     * @var array
     */
    protected $uploadedDocuments = array();

    /**
     * The documents in the queue to be deleted
     * @var array
     */
    protected $removedDocuments = array();

    /**
     * The fields to facet the uploaded documents on
     * @var array
     */
    protected $facetFields = array();

    /**
     * The transport to use
     * @var \vierbergenlars\Norch\Transport\TransportInterface
     */
    protected $transport;

    /**
     * Creates a new search index
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport
     * @param array $documents The documents to upload. The array key is the document ID.
     * @param array $facetFields The fields to facet on
     */
    public function __construct(TransportInterface $transport, array $documents = array(), array $facetFields = array())
    {
        foreach($documents as $document)
            $this->addDocument($document);
        foreach($facetFields as $field)
            $this->addFacetField ($field);
        $this->transport = $transport;
    }

    /**
     * Adds a field to facet on
     * @param string $field
     * @return \vierbergenlars\Norch\SearchIndex\Index
     */
    public function addFacetField($field)
    {
        $this->facetFields[$field] = true;
        return $this;
    }

    /**
     * Removes a field that was faceted on
     * @param string $field
     * @return \vierbergenlars\Norch\SearchIndex\Index
     */
    public function removeFacetField($field)
    {
        unset($this->facetFields[$field]);
        return $this;
    }

    /**
     * Adds a new document to the index
     * @param array $document Should contain a parameter 'id', that will be used as an id
     * @return \vierbergenlars\Norch\SearchIndex\Index
     */
    public function addDocument($document)
    {
        if (!is_array($document))
            throw new \BadMethodCallException('Parameter 1 of ' . __METHOD__ . ' should be array, ' . (is_object($document) ? get_class($document) : gettype($document)) . ' given.');
        $id = $document['id'];
        unset($document['id']);
        unset($this->removedDocuments[$id]);
        $this->uploadedDocuments[$id] = $document;
        return $this;
    }

    /**
     * Removes a document from the index
     * @param int|string $id The id of the document
     * @return \vierbergenlars\Norch\SearchIndex\Index
     */
    public function removeDocument($id)
    {
        if(isset($this->uploadedDocuments[$id]))
            unset($this->uploadedDocuments[$id]);
        $this->removedDocuments[$id] = true;
        return $this;
    }

    /**
     * Sends all changes to the index to the transport
     * @return bool
     */
    public function flush()
    {
        $statuses = array();
        foreach($this->removedDocuments as $id=>$_)
        {
            $statuses[] = $this->transport->deleteDoc($id);
        }
        if(count($this->uploadedDocuments) > 0)
            $statuses[] = $this->transport->indexBatch($this->uploadedDocuments, array_keys($this->facetFields));

        $this->removedDocuments = array();
        $this->uploadedDocuments = array();

        return !in_array(false, $statuses);
    }
}
