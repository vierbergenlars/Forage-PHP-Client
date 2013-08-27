<?php

namespace test\transport;

use vierbergenlars\Forage\Transport\Http as HttpTransport;

class http extends \UnitTestCase
{
    private $transport;
    function __construct()
    {
        $this->transport = new HttpTransport;
    }

    function testIndexing()
    {
        $originalIndexData = $this->transport->getIndexMetadata();

        $this->assertTrue($this->transport->indexBatch(array(
            array(
                'title'=>'Lol Cat',
                'categories'=> array('images', 'cat', 'funny', 'random'),
                'body'=>'cat cat cat cat lol!'
            ),
            array(
                'title'=>'Lol Dog',
                'categories'=> array('images', 'funny', 'dog'),
                'body'=>'dog dog lol!!'
            ),
            array(
                'title'=>'Whatever',
                'categories'=>array('random'),
                'body'=>'cat /dev/urandom... lol'
            )
        ), array(
            'categories'
        )));

        $indexData = $this->transport->getIndexMetadata();
        $this->assertEqual($indexData['totalDocs'] - $originalIndexData['totalDocs'], 3);
        $this->assertEqual($indexData['availableFacets'], array('categories'));
        $this->assertEqual($indexData['indexedFieldNames'], array('title', 'categories', 'body'));
    }

    function testBasicSearch()
    {

        $lolSearch = $this->transport->search('Lol');

        $this->assertEqual($lolSearch['totalHits'], 3);
        $this->assertEqual($lolSearch['facets'], array(
            'categories'=> array(
                'images'=>2,
                'cat'=>1,
                'funny'=>2,
                'random'=>2,
                'dog'=>1
            )
        ));
        $this->assertEqual($lolSearch['hits'][0]['matchedTerms'],array(
            'lol'=> array('body'=>0.5, 'title'=>1)
        ));
        $this->assertEqual($lolSearch['hits'][0]['document'],array(
            'title'=>'Lol Dog',
            'categories'=> array('images', 'funny', 'dog'),
            'body'=>'dog dog lol!!',
            'id'=>1
        ));
        $this->assertNotNull($lolSearch['hits'][0]['score']);

        $lolCatSearch = $this->transport->search('Lol Cat');
        $this->assertEqual($lolCatSearch['totalHits'], 2);
    }

    function testFieldedSearch()
    {
        $lolSearch = $this->transport->search('Lol', array('title'));
        $this->assertEqual($lolSearch['totalHits'], 2);

        $catTitleSearch = $this->transport->search('cat', array('title'));
        $this->assertEqual($catTitleSearch['totalHits'], 1);

        $catBodyCategorySearch = $this->transport->search('cat', array('body', 'category'));
        $this->assertEqual($catBodyCategorySearch['totalHits'], 2);

    }

    function testFacetedSearch()
    {
        $lolSearch = $this->transport->search('lol', null, array('categories', 'body'));
        $this->assertEqual($lolSearch['facets'], array(
            "categories"=> array(
                "images"=> 2,
                "cat"=> 1,
                "funny"=> 2,
                "random"=> 2,
                "dog"=> 1
              ),
            "body"=>array(
                "c"=> 5,
                "a"=> 6,
                "t"=> 5,
                " "=> 8,
                "l"=> 6,
                "o"=> 6,
                "!"=> 3,
                "d"=> 4,
                "g"=> 2,
                "/"=> 2,
                "e"=> 1,
                "v"=> 1,
                "u"=> 1,
                "r"=> 1,
                "n"=> 1,
                "m"=> 1,
                "."=> 3
              )
        ));
    }

    function testFilteredSearch()
    {
        $lolSearch = $this->transport->search('lol', null, null, array('categories'=> array('random')));
        $this->assertEqual($lolSearch['totalHits'], 2);
    }

    function testPaginatedSearch()
    {
        $lolSearch = $this->transport->search('lol', null, null, null, 1, 1);
        $this->assertEqual($lolSearch['totalHits'], 3);
        $this->assertEqual(count($lolSearch['hits']), 1);
    }

    function testWeighedSearch()
    {
        $lolSearch = $this->transport->search('lol', null, null, null, 0, 10, array('body'=>array(4)));
        $this->assertEqual($lolSearch['hits'][0]['document']['title'], 'Whatever');

    }

    function testDelete()
    {
        $originalIndexData = $this->transport->getIndexMetadata();
        $this->assertTrue($this->transport->deleteDoc(0));
        $indexData = $this->transport->getIndexMetadata();
        $this->assertEqual($indexData['totalDocs'] - $originalIndexData['totalDocs'], -1);

        $lolSearch = $this->transport->search('Lol');
        $this->assertEqual($lolSearch['totalHits'], 2);
    }

}
