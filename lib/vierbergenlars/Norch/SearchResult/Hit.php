<?php

namespace vierbergenlars\Norch\SearchResult;

/**
 * A search hit
 */
class Hit
{
    /**
     * The terms that matched in the document
     *
     * @var array
     */
    private $matchedTerms;

    /**
     * The matched document
     *
     * @var array
     */
    private $document;

    /**
     * The result score for the document
     * @var float
     */
    private $score;

    /**
     * Creates a new hit object
     *
     * @private
     * @param array $hit_array
     */
    public function __construct(array $hit_array)
    {
        $this->matchedTerms = $hit_array['matchedTerms'];
        $this->document = $hit_array['document'];
        $this->score = $hit_array['score'];
    }

    /**
     * Gets the score of the document for the search query
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Gets the terms that matched in the document
     *
     * @return array
     */
    public function getMatchedTerms()
    {
        return $this->matchedTerms;
    }

    /**
     * Gets the document
     *
     * @return array
     */
    public function getDocument()
    {
        return $this->document;
    }
}
