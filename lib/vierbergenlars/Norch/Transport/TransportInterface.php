<?php

namespace vierbergenlars\Norch\Transport;

/**
 * Interface all transport objects should implement
 *
 * The Transport layer provides a low-level interface to the Norch search server.
 * The transport layer is responsible for serializing of the passed data,
 * and unserialisation of the received data. It should not modify requests or responses.
 */
interface TransportInterface
{
    /**
     * Adds a set of documents to the index
     *
     * @param array $documents An array of documents to submit. Each document is an array. The document may not have a key named 'id'.
     * @param array $filter Array of fields that can be used for faceted search. Can only contain fields that are arrays in the document.
     * @return boolean
     * @throws TransportException
     */
    public function indexBatch(array $documents, array $filter);

    /**
     * Perform a search query
     *
     * @param string $query The search query
     * @param array $searchFields An array of fields to search in
     * @param array $facets An array of fields to facet on
     * @param array $filters Limit search to fields with a particular value(s). Each field contains an array of acceptable values.
     * @param int $offset The offset to start in the result set
     * @param int $pagesize The size of each page
     * @param array $weight The weights to give each field.
     * @return array Raw json decoded data from the search server
     * @throws TransportException
     */
    public function search(
              $query,
        array $searchFields = null,
        array $facets       = null,
        array $filters      = null,
              $offset       = 0,
              $pagesize     = 10,
        array $weight       = null
    );

    /**
     * Removes a document with a specific ID
     *
     * @param int|string $docId
     * @return boolean
     * @throws TransportException
     */
    public function deleteDoc($docId);

    /**
     * Retrieve meta-data about the index
     *
     * @return array The raw JSON decoded data from the search server
     * @throws TransportException
     */
    public function getIndexMetadata();
}