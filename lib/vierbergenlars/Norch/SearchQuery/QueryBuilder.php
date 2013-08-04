<?php

namespace vierbergenlars\Norch\SearchQuery;

/**
 * Helps building search queries
 */
class QueryBuilder
{
    /**
     * The query that is under construction
     * @var \vierbergenlars\Norch\SearchQuery\Query
     */
    protected $query;

    /**
     * Creates a new query builder
     * @param \vierbergenlars\Norch\SearchQuery\Query $query Override the query object that is built
     */
    public function __construct($query = null)
    {
        if(!$query)
            $this->query = new Query;
        else
            $this->query = $query;
    }

    /**
     * Sets the search query
     * @param string $query
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function setSearchQuery($query)
    {
        $this->query->setQuery($query);
        return $this;
    }

    /**
     * Sets the offset for the search query
     * @param int $offset
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function setOffset($offset)
    {
        $this->query->setOffset($offset);
        return $this;
    }

    /**
     * Sets the limit for the search query
     * @param int $limit
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function setLimit($limit)
    {
        $this->query->setLimit($limit);
        return $this;
    }

    /**
     * Adds a new field to search in
     * @param string $field
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function addSearchField($field)
    {
        $this->_addField('searchFields', $field);
        return $this;
    }

    /**
     * Removes a field from the list to search in
     * @param string $field
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function removeSearchField($field)
    {
        $this->_removeField('searchFields', $field);
        return $this;
    }

    /**
     * Adds a new facet
     * @param string $facet
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function addFacet($facet)
    {
        $this->_addField('facetFields', $facet);
        return $this;
    }

    /**
     * Removes a facet
     * @param string $facet
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function removeFacet($facet)
    {
        $this->_removeField('facetFields', $facet);
        return $this;
    }

    /**
     * Adds a filter on a field
     * @param string $field The field to add a filter to
     * @param string|array $value The value(s) to limit the field to
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function addFilter($field, $value)
    {
        $this->_addFieldArray('searchFilters', $field, $value);
        return $this;
    }

    /**
     * Removes a filter on a field
     * @param string $field The field to remove a filter from
     * @param string|array|null $value The values to remove from the filter. If `null`, remove all filters on the field.
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function removeFilter($field, $value=null)
    {
        $this->_removeFieldArray('searchFilters', $field, $value);
        return $this;
    }

    /**
     * Adds a weight to a field
     * @param string $field
     * @param int|array $value
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function addWeight($field, $value)
    {
        $this->_addFieldArray('weights', $field, $value);
        return $this;
    }

    /**
     * Removes a weight from a field
     * @param string $field
     * @param int|array $value
     * @return \vierbergenlars\Norch\SearchQuery\QueryBuilder
     */
    public function removeWeight($field, $value=null)
    {
        $this->_removeFieldArray('weights', $field, $value);
        return $this;
    }

    /**
     * Gets the completed query
     * @return \vierbergenlars\Norch\SearchQuery\Query
     */
    public function getQuery()
    {
        return clone $this->query;
    }

    public function __clone()
    {
        $this->query = clone $this->query;
    }

    private function _addField($type, $name)
    {
        if(!in_array($name, $this->query->$type))
            $this->query->{$type}[] = $name;
    }

    private function _removeField($type, $name)
    {
        $key = array_search($name, $this->query->$type);
        unset($this->query->{$type}[$key]);
        // Reset array to numeric order
        $this->query->{$type} = array_values($this->query->{$type});

    }

    private function _addFieldArray($type, $name, $value)
    {
        if(!isset($this->query->{$type}[$name]))
            $this->query->{$type}[$name] = array();

        if(is_array($value)) {
            $this->query->{$type}[$name] = array_merge($this->query->{$type}[$name], $value);
        } else {
            $this->query->{$type}[$name][] = $value;
        }
    }

    private function _removeFieldArray($type, $name, $value)
    {
        if(!isset($this->query->{$type}[$name]))
            return;

        if(is_null($value)) {
            unset($this->query->{$type}[$name]);
        } elseif(is_array($value)) {
            foreach($value as $v)
                $this->_removeFieldArray($type, $name, $v);
        } else {
            $key = array_search($value, $this->query->{$type}[$name]);
            unset($this->query->{$type}[$name][$key]);
            // Reset array keys to numeric order
            $this->query->{$type}[$name] = array_values($this->query->{$type}[$name]);
        }
    }
}
