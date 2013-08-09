<?php

namespace test\odm;

use vierbergenlars\Norch\ODM\SearchIndex as Index;
use test\searchindex\TransportMock;
use vierbergenlars\Norch\ODM\HydrationSettings\SingleObjectHydration;

class searchindex extends \UnitTestCase
{
    function testSearchIndex()
    {
        $transport = new TransportMock;
        $hydrationSettings = new SingleObjectHydration('test\odm\DocumentObject');
        $index = new Index($transport, $hydrationSettings);
        $doc1 = new DocumentObject('lolol', 'sofsnfosq', array('a','b'));
        $index->addDocument($doc1);
        $doc2 = clone $doc1;
        $index->addDocument($doc2);
        $doc3 = new DocumentObject('lol', 'oqnosnoqs', array('b','c'));
        $index->addDocument($doc3);
        $doc4 = new DocumentObject('snfosn','osnfosno', array('qsf','sfs'));
        $index->addDocument($doc4);

        $index->removeDocument($doc3);
        $index->removeDocument($doc4->getIdReal());
        $index->removeDocument('qsfsf');

        $index->addFacetField('categories');

        $result = $index->flush();

        $this->assertTrue($result);
        $this->assertEqual($transport->deleteCalls, 3);
        $this->assertEqual($transport->deleteArguments, array($doc3->getIdReal(),
            $doc4->getIdReal(), 'qsfsf'));
        $this->assertEqual($transport->indexCalls, 1);
        $this->assertEqual($transport->indexArguments, array(
            array(
                $doc1->getIdReal() => array('title' => 'lolol', 'body' => 'sofsnfosq',
                    'categories' => array('a', 'b'))
            ),
            array('categories')
        ));
    }
}
