<?php

namespace test\searchquery;

use vierbergenlars\Norch\SearchQuery\Query as SearchQuery;

class query extends \UnitTestCase
{
    function testSimpleQuery()
    {
        $transport = new TransportMock;
        $query = new SearchQuery($transport, 'lol');

        $this->assertIsA($query->execute(), 'vierbergenlars\Norch\SearchResult\SearchResult');

        $this->assertEqual($transport->calls, 1, 'The query function should be called exactly once');
        $this->assertEqual($transport->arguments, array('lol', array(), array(), array(), 0, 10, array()));
    }

    function testFullQuery()
    {
        $transport = new TransportMock;
        $query = new SearchQuery($transport);

        $result = $query->setQuery('lol')
                ->setSearchFields(array('categories'))
                ->setFacetFields(array('categories'))
                ->setSearchFilters(array('title'=>array('Whatever')))
                ->setOffset(2)
                ->setLimit(3)
                ->setWeights(array('body'=>array(3)))
                ->execute();

        $this->assertEqual($query->query, 'lol');
        $this->assertEqual($query->searchFields, array('categories'));
        $this->assertEqual($query->facetFields, array('categories'));
        $this->assertEqual($query->searchFilters, array('title'=> array('Whatever')));
        $this->assertEqual($query->offset, 2);
        $this->assertEqual($query->limit, 3);
        $this->assertEqual($query->weights, array('body'=>array(3)));

        $this->assertIsA($result, 'vierbergenlars\Norch\SearchResult\SearchResult');
        $this->assertEqual($transport->calls, 1, 'The query function should be called exactly once');
        $this->assertEqual($transport->arguments, array('lol', array('categories'), array('categories'), array('title'=>array('Whatever')), 2, 3, array('body'=>array(3))));

    }
}
