<?php

namespace test\searchindex;

use vierbergenlars\Norch\SearchIndex\TransportAwareIndex as TIndex;

class transportawareindex extends \UnitTestCase
{
    function testTransportIndex()
    {
        $transport = new TransportMock;
        $index = new TIndex($transport);

        $index->addDocument(1, array('title'=>'a'))
                ->addDocument(2, array('title'=>'b'))
                ->addDocument(3, array('title'=>'c'))
                ->addDocument(4, array('title'=>'d'));

        $index->removeDocument(2)
                ->removeDocument(4)
                ->removeDocument(5)
                ->removeDocument(6);

        $index->addDocument(5, array('title'=>'e'));

        $status = $index->flush();

        $this->assertTrue($status);

        $this->assertEqual($transport->deleteCalls, 3);
        $this->assertEqual($transport->deleteArguments, array(2,4,6));

        $this->assertEqual($transport->indexCalls, 1);
        $this->assertEqual($transport->indexArguments, array(
            array(
                1=>array('title'=>'a'),
                3=>array('title'=>'c'),
                5=>array('title'=>'e')
            ), array()
        ));
    }
}
