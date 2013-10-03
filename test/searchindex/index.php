<?php

namespace test\searchindex;

use vierbergenlars\Forage\SearchIndex\Index as SearchIndex;

class index extends \UnitTestCase
{
    public function testSearchIndexUpload()
    {
        $transport = new TransportMock;
        $index = new SearchIndex($transport, array(
            'doc1'=> array ('title' => 'a', 'body' => 'b', 'categories' => array (
                    'a', 'b')),
            'doc2'=>array ('title' => 'b', 'body' => 'c', 'categories' => array (
                    'b', 'c'))
                ), array('categories', 'body'));

        $index->addDocument('doc3', array ('title' => 'c', 'body' => 'asdfasdf',
                    'categories' => array ('c', 'a', 'd')))
                ->addFacetField('title')
                ->removeFacetField('body');

        $status = $index->flush();

        $this->assertTrue($status);

        $this->assertEqual($transport->deleteCalls, 0);
        $this->assertEqual($transport->indexCalls, 1);
        $this->assertEqual($transport->indexArguments, array(
            array(
                'doc1'=>array('title'=>'a', 'body'=>'b', 'categories'=>array('a','b')),
                'doc2'=>array('title'=>'b', 'body'=>'c', 'categories'=>array('b','c')),
                'doc3'=>array('title'=>'c', 'body'=>'asdfasdf', 'categories'=> array('c', 'a', 'd'))
            ),
            array('categories', 'title')
        ));

    }

    function testSearchIndexDelete()
    {
        $transport = new TransportMock;
        $index = new SearchIndex($transport);

        $index->removeDocument('doc1')
                ->removeDocument('doc2')
                ->removeDocument('doc5');

        $status = $index->flush();

        $this->assertTrue($status);

        $this->assertEqual($transport->indexCalls, 0);
        $this->assertEqual($transport->deleteCalls, 3);
        $this->assertEqual($transport->deleteArguments, array('doc1', 'doc2', 'doc5'));
    }

    function testSearchIndexAddDelete()
    {
        $transport = new TransportMock;
        $index = new SearchIndex($transport);

        $index->addDocument(1, array ('title' => 'a'))
                ->addDocument(2, array ('title' => 'b'))
                ->addDocument(3, array ('title' => 'c'))
                ->addDocument(4, array ('title' => 'd'));

        $index->removeDocument(2)
                ->removeDocument(4)
                ->removeDocument(5)
                ->removeDocument(6);

        $index->addDocument(5, array ('title' => 'e'));

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
