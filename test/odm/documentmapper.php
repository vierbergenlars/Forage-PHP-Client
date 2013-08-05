<?php

namespace test\odm;

use vierbergenlars\Norch\ODM\DocumentMapper as DM;
use vierbergenlars\Norch\ODM\HydrationSettings\SingleObjectHydration;
use test\searchquery\TransportMock;

class documentmapper extends \UnitTestCase
{
    function testDocumentMapper()
    {
        $transport = new TransportMock;
        $hydationSettings = new SingleObjectHydration(__NAMESPACE__ . '\DocumentObject');
        $dm = new DM($transport, $hydationSettings);


        $query = $dm->createQueryBuilder()->getQuery();
        $this->assertIsA($query, 'vierbergenlars\Norch\ODM\SearchQuery');
        foreach($query->execute() as $hit)
        {
            $this->assertIsA($hit->getDocument(), __NAMESPACE__.'\DocumentObject');
        }
        $this->assertIsA($dm->getIndex(), 'vierbergenlars\Norch\ODM\SearchIndex');

    }
}
