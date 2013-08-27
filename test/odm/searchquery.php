<?php

namespace test\odm;

use vierbergenlars\Forage\ODM\SearchQuery as Query;
use vierbergenlars\Forage\ODM\HydrationSettings\SingleObjectHydration;
use test\searchquery\TransportMock;

class searchquery extends \UnitTestCase
{
    function testSearchQuery()
    {
        $transport = new TransportMock;
        $hydationSettings = new SingleObjectHydration(__NAMESPACE__ . '\DocumentObject');

        $query = new Query($transport, $hydationSettings);
        $result = $query->execute();

        $this->assertIsA($result, 'vierbergenlars\Forage\ODM\SearchResult');
        foreach($result as $hit)
        {
            $this->assertIsA($hit->getDocument(), __NAMESPACE__.'\DocumentObject');
        }
    }
}