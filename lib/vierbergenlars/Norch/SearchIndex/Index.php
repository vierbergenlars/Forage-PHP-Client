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
    private $uploadedDocuments = array();

    /**
     * The documents in the queue to be deleted
     * @var array
     */
    private $removedDocuments = array();

    /**
     * The fields to facet the uploaded documents on
     * @var array
     */
    private $facetFields = array();

    /**
     * Creates a new search index
     * @param array $documents The documents to upload. The array key is the document ID.
     * @param array $facetFields The fields to facet on
     */
    public function __construct(array $documents = array(), array $facetFields = array())
    {
        foreach($documents as $id=>$document)
            $this->addDocument($id, $document);
        foreach($facetFields as $field)
            $this->addFacetField ($field);
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
     * @param int|string $id The id of the document
     * @param array $document
     * @return \vierbergenlars\Norch\SearchIndex\Index
     */
    public function addDocument($id, array $document)
    {
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
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport
     * @return bool
     */
    public function flush(TransportInterface $transport)
    {
        $statuses = array();
        foreach($this->removedDocuments as $id=>$_)
        {
            $statuses[] = $transport->deleteDoc($id);
        }
        if(count($this->uploadedDocuments) > 0)
            $statuses[] = $transport->indexBatch($this->uploadedDocuments, array_keys($this->facetFields));

        if(!in_array(false, $statuses)) {
            $this->removedDocuments = array();
            $this->uploadedDocuments = array();
            return true;
        }
        return false;
    }
}
