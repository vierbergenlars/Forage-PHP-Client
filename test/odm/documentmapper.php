<?php

namespace test\odm;

use vierbergenlars\Forage\ODM\DocumentMapper as DM;
use vierbergenlars\Forage\ODM\HydrationSettings\SingleObjectHydration;
use test\searchquery\TransportMock;

class documentmapper extends \UnitTestCase
{
    function testDocumentMapper()
    {
        $transport = new TransportMock;
        $hydationSettings = new SingleObjectHydration(__NAMESPACE__ . '\DocumentObject');
        $dm = new DM($transport, $hydationSettings);


        $query = $dm->createQueryBuilder()->getQuery();
        $this->assertIsA($query, 'vierbergenlars\Forage\ODM\SearchQuery');
        foreach($query->execute() as $hit)
        {
            $this->assertIsA($hit->getDocument(), __NAMESPACE__.'\DocumentObject');
        }
        $this->assertIsA($dm->getIndex(), 'vierbergenlars\Forage\ODM\SearchIndex');

    }
}
