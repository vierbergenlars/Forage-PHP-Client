<?php

namespace test\odm;

use vierbergenlars\Norch\ODM\SearchHit as Hit;

class searchhit extends \UnitTestCase
{
    function testHydration()
    {
        $document = array(
            'matchedTerms'=> array(
                'lol'=>array(
                    'title'=>1
                ),
                'asdf'=>array(
                    'body'=>0.5
                )
            ),
            'document'=> array(
                'title'=>'Lol docu!',
                'body'=>'asdf osnfoeoq asofns sfqsf',
                'categories'=>array('a','0xA', '0xFFF', 'sfefqs'),
                'id'=>5
            ),
            'score'=>1.2684648946
        );

        $hit = new Hit($document);

        $hit->hydrateObject(__NAMESPACE__.'\DocumentObject');

        $this->assertIsA($hit->getDocument(), __NAMESPACE__.'\DocumentObject');
        $doc = $hit->getDocument();
        $this->assertEqual($doc->getTitle(), $document['document']['title']);
        $this->assertEqual($doc->getBody(), $document['document']['body']);
        $this->assertEqual($doc->getCategories()->getArrayCopy(), $document['document']['categories']);
        $this->assertEqual($doc->getId(), $document['document']['id']);
    }
}