<?php

namespace test\odm;

use vierbergenlars\Norch\ODM\SearchQuery as Query;
use test\searchquery\TransportMock;

class searchquery extends \UnitTestCase
{
    function testSearchQuery()
    {
        $transport = new TransportMock;

        $query = new Query($transport, __NAMESPACE__.'\DocumentObject');
        $result = $query->execute();

        $this->assertIsA($result, 'vierbergenlars\Norch\ODM\SearchResult');
        foreach($result as $hit)
        {
            $this->assertIsA($hit->getDocument(), __NAMESPACE__.'\DocumentObject');
        }
    }
}