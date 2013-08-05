<?php

namespace test;

use vierbergenlars\Norch\Client as NorchClient;
use vierbergenlars\Norch\ODM\HydrationSettings\SingleObjectHydration;

class client extends \UnitTestCase
{
    function testCreateQueryBuilder()
    {
        $transport = new searchquery\TransportMock;
        $client = new NorchClient($transport);

        $this->assertIsA($client->createQueryBuilder(), 'vierbergenlars\Norch\SearchQuery\QueryBuilder');
        $this->assertIsA($client->createQueryBuilder()->getQuery(), 'vierbergenlars\Norch\SearchQuery\Query');
    }

    function testGetIndex()
    {
        $transport = new searchindex\TransportMock;
        $client = new NorchClient($transport);

        $this->assertIsA($client->getIndex(), 'vierbergenlars\Norch\SearchIndex\Index');
    }

    function testCreateDocumentMapper()
    {
        $transport = new searchquery\TransportMock;
        $hydrationSettings = new SingleObjectHydration('test\odm\DocumentObject');
        $client = new NorchClient($transport);

        $this->assertIsA($client->createDocumentMapper($hydrationSettings),
                                                       'vierbergenlars\Norch\ODM\DocumentMapper');
    }
}
