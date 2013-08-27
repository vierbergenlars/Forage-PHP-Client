<?php

namespace test;

use vierbergenlars\Forage\Client as ForageClient;
use vierbergenlars\Forage\ODM\HydrationSettings\SingleObjectHydration;

class client extends \UnitTestCase
{
    function testCreateQueryBuilder()
    {
        $transport = new searchquery\TransportMock;
        $client = new ForageClient($transport);

        $this->assertIsA($client->createQueryBuilder(), 'vierbergenlars\Forage\SearchQuery\QueryBuilder');
        $this->assertIsA($client->createQueryBuilder()->getQuery(), 'vierbergenlars\Forage\SearchQuery\Query');
    }

    function testGetIndex()
    {
        $transport = new searchindex\TransportMock;
        $client = new ForageClient($transport);

        $this->assertIsA($client->getIndex(), 'vierbergenlars\Forage\SearchIndex\Index');
    }

    function testCreateDocumentMapper()
    {
        $transport = new searchquery\TransportMock;
        $hydrationSettings = new SingleObjectHydration('test\odm\DocumentObject');
        $client = new ForageClient($transport);

        $this->assertIsA($client->createDocumentMapper($hydrationSettings),
                                                       'vierbergenlars\Forage\ODM\DocumentMapper');
    }
}
