<?php

namespace test;

use vierbergenlars\Norch\Client as NorchClient;

class client extends \UnitTestCase
{
    function testCreateQueryBuilder()
    {
        $transport = new searchquery\TransportMock;
        $client = new NorchClient($transport);

        $this->assertIsA($client->createQueryBuilder(), 'vierbergenlars\Norch\SearchQuery\QueryBuilder');
        $this->assertIsA($client->createQueryBuilder()->getQuery(), 'vierbergenlars\Norch\SearchQuery\TransportAwareQuery');
    }

    function testGetIndex()
    {
        $transport = new searchindex\TransportMock;
        $client = new NorchClient($transport);

        $this->assertIsA($client->getIndex(), 'vierbergenlars\Norch\SearchIndex\TransportAwareIndex');
    }

    function testCreateDocumentMapper()
    {
        $transport = new searchquery\TransportMock;
        $client = new NorchClient($transport);

        $this->assertIsA($client->createDocumentMapper(__NAMESPACE__.'\odm\DocumentObject'), 'vierbergenlars\Norch\ODM\DocumentMapper');
    }
}
