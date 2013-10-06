<?php

namespace vierbergenlars\Forage\SearchResult;

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
    protected $totalHits = 0;

    /**
     * The facets for the search query
     *
     * @var array
     */
    protected $facets = array();

    /**
     * The class that is instanciated for a hit
     * @var string
     */
    protected $hitClass = '\vierbergenlars\Forage\SearchResult\Hit';

    /**
     * The class that is instanciated for a facet
     * @var string
     */
    protected $facetClass = '\vierbergenlars\Forage\SearchResult\Facet';

    /**
     * Creates a new SearchResult object
     *
     * @internal
     * @param array $results The result array from the transport layer
     */
    public function __construct(array $results)
    {
        $this->totalHits = $results['totalHits'];
        $facetClass = $this->facetClass;
        $hitClass = $this->hitClass;
        foreach($results['facets'] as $field => $facets) {
            if(count($facets) > 1) // Don't add facets that have at most one result, it's pointless to facet on those.
                $this->facets[] = new $facetClass($field, $facets);
        }
        $hits = array();
        foreach($results['hits'] as $hit) {
            $hits[] = new $hitClass($hit);
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
