<?php

namespace test\searchquery;

use vierbergenlars\Norch\SearchQuery\QueryBuilder as SearchQueryBuilder;

class querybuilder extends \UnitTestCase
{
    function testQueryBuilderAdd()
    {
        $builder = new SearchQueryBuilder;
        $query = $builder->setSearchQuery('lol')
                ->addSearchField('title')
                ->addSearchField('categories')
                ->addFacet('categories')
                ->addFacet('tags')
                ->addFilter('tags', array('a','b','c'))
                ->addFilter('categories', 'a')
                ->addFilter('categories', array('c','b'))
                ->addWeight('title', 3)
                ->addWeight('body', 0.5)
                ->setOffset(2)
                ->setLimit(20)
                ->getQuery();

        $this->assertEqual($query->query, 'lol');
        $this->assertEqual($query->searchFields, array('title', 'categories'));
        $this->assertEqual($query->facetFields, array('categories', 'tags'));
        $this->assertEqual($query->searchFilters, array(
            'tags'=>array('a','b','c'),
            'categories'=>array('a','c','b')
        ));
        $this->assertEqual($query->weights, array('title'=>array(3), 'body'=>array(0.5)));
        $this->assertEqual($query->offset, 2);
        $this->assertEqual($query->limit, 20);

    }

    function testQueryBuilderRemove()
    {
        $builder = new SearchQueryBuilder;
        $q1 = $builder->addFacet('a')
                ->addFacet('b')
                ->removeFacet('a')
                ->addSearchField('a')
                ->addSearchField('b')
                ->removeSearchField('a')
                ->getQuery();
        $this->assertEqual($q1->facetFields, array('b'));
        $this->assertEqual($q1->searchFields, array('b'));


        $builder = new SearchQueryBuilder;
        $q2 = $builder->addFilter('a', 'a')
                ->addFilter('a', array('b','c'))
                ->removeFilter('a', 'b')
                ->getQuery();
        $this->assertEqual($q2->searchFilters, array('a'=>array('a','c')));

        $q3 = $builder->addFilter('a', array('b','d','e'))
                ->removeFilter('a', array('d','a'))
                ->getQuery();
        $this->assertEqual($q3->searchFilters, array('a'=>array('c','b','e')));

        $q4 = $builder->addFilter('b', array('a','b'))
                ->removeFilter('a')
                ->getQuery();
        $this->assertEqual($q4->searchFilters, array('b'=>array('a','b')));

        $q5 = $builder->addWeight('a', 2)
                ->addWeight('d', 5)
                ->removeWeight('a')
                ->addWeight('b', array(2,3,4,5))
                ->removeWeight('b', array(3,4))
                ->getQuery();
        $this->assertEqual($q5->searchFilters, $q4->searchFilters);
        $this->assertEqual($q5->weights, array('d'=>array(5), 'b'=>array(2,5)));

    }
}
