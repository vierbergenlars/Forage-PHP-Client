<?php

namespace vierbergenlars\Norch\SearchResult;

/**
 * A search result
 */
class SearchResult extends \ArrayObject
{
    /**
     * The total number of hits the search query generated
     *
     * @var int
     */
    private $totalHits = 0;

    /**
     * The facets for the search query
     *
     * @var array
     */
    private $facets = array();

    /**
     * Creates a new SearchResult object
     *
     * @private
     * @param array $result_array The result array from the transport layer
     */
    public function __construct(array $result_array)
        {
        $this->totalHits = $result_array['totalHits'];
        foreach($result_array['facets'] as $field=>$results) {
            $this->facets[] = new Facet($field, $results);
        }
        $hits = array();
        foreach($result_array['hits'] as $hit) {
            $hits[] = new Hit($hit);
        }
        parent::__construct($hits);
    }

    /**
     * Gets the facets for the search query
     *
     * @return array
     */
    public function getFacets()
    {
        return $this->facets;
    }

    /**
     * Gets the hits (results) for the search query
     *
     * @return array
     */
    public function getHits()
    {
        return $this->getArrayCopy();
    }

    /**
     * Gets the total number of hits for the search.
     *
     * This number may not be equal to the number of hits that are received by getHits()
     * @return int
     */
    public function getTotalHits()
    {
        return $this->totalHits;
    }
}
