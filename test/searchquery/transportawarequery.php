<?php

namespace test\searchquery;

use vierbergenlars\Norch\SearchQuery\TransportAwareQuery as TQuery;

class transportawarequery extends \UnitTestCase
{
    function testTransportQuery()
    {
        $transport = new TransportMock;
        $query = new TQuery($transport, 'lol');

        $this->assertIsA($query->execute(), 'vierbergenlars\Norch\SearchResult\SearchResult');
        $this->assertEqual($transport->calls, 1, 'The query function should only be called once');
        $this->assertEqual($transport->arguments, array('lol', array(), array(), array(), 0, 10, array()));
    }
}
