<?php

namespace vierbergenlars\Norch\SearchResult;

/**
 * A facet
 */
class Facet extends \ArrayObject
{
    /**
     * The name of the faceted field
     *
     * @var string
     */
    private $field;

    /**
     * Creates a new facet
     *
     * @private
     * @param string $field
     * @param array $results
     */
    public function __construct($field, $results)
    {
        $this->field = $field;
        parent::__construct($results);
    }

    /**
     * Gets the name of the faceted field
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Gets the results of the faceted field
     *
     * @return array
     */
    public function getResults()
    {
        return $this->getArrayCopy();
    }
}
