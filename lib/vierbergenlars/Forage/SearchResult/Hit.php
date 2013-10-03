<?php

namespace vierbergenlars\Forage\SearchResult;

/**
 * A search hit
 */
class Hit
{
    /**
     * The id of the document that matched
     *
     * @var string|int
     */
    protected $id;

    /**
     * The terms that matched in the document
     *
     * @var array
     */
    protected $matchedTerms;

    /**
     * The matched document
     *
     * @var array
     */
    protected $document;

    /**
     * The result score for the document
     * @var float
     */
    protected $score;

    /**
     * Creates a new hit object
     *
     * @private
     * @param array $hit_array
     */
    public function __construct(array $hit_array)
    {
        $this->id = $hit_array['id'];
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

    /**
     * Gets the id of the document
     *
     * @return string|int
     */
    public function getId()
    {
        return $this->id;
    }
}
