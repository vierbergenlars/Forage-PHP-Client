<?php

namespace test\searchresult;

use vierbergenlars\Norch\SearchResult\Hit as SearchHit;

class hit extends \UnitTestCase
{
    function testHit()
    {
        $hit = new SearchHit(array(
            "matchedTerms"=> array(
              "lol"=> array(
                "body"=> "0.5",
                "title"=> "1"
              )
            ),
            "document"=> array(
              "title"=> "Lol Dog",
              "categories"=> array(
                "images",
                "funny",
                "dog"
              ),
              "body"=> "dog dog lol!!",
              "id"=> "1"
            ),
            "score"=> 51651.64
        ));

        $this->assertEqual($hit->getMatchedTerms(), array(
            'lol'=> array(
                'body'=>'0.5',
                'title'=>'1'
            )
        ));

        $this->assertEqual($hit->getScore(), 51651.64);

        $this->assertEqual($hit->getDocument(), array(
              "title"=> "Lol Dog",
              "categories"=> array(
                "images",
                "funny",
                "dog"
              ),
              "body"=> "dog dog lol!!",
              "id"=> "1"
        ));
    }
}
