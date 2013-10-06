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
     * @internal
     * @param array $hit
     */
    public function __construct(array $hit)
    {
        $this->id = $hit['id'];
        $this->matchedTerms = $hit['matchedTerms'];
        $this->document = $hit['document'];
        $this->score = $hit['score'];
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
