<?php

namespace test\searchresult;

use vierbergenlars\Forage\SearchResult\SearchResult as Result;

class searchresult extends \UnitTestCase
{
    function testSearchResultEmpty()
    {
        $result_array = json_decode('{
  "idf": {
    "lollknk": null
  },
  "query": {
    "query": [
      "lollknk"
    ],
    "offset": 0,
    "pagesize": 10
  },
  "transformedQuery": {
    "query": [
      "lollknk"
    ]
  },
  "totalHits": 0,
  "facets": {
    "categories": {}
  },
  "hits": []
}', true);
        $result = new Result($result_array);

        $this->assertEqual($result->count(),0);
        $this->assertEqual($result->getHits(), array());
        $this->assertEqual(count($result->getFacets()), 0); // Empty facets are not founted
        $this->assertEqual($result->getTotalHits(), 0);

        foreach($result as $hit)
        {
            $this->fail('There are no hits, the iterator should not run.');
        }

        foreach($result->getFacets() as $facet)
        {
            $this->fail('There should be nothing to facet on.');
        }
    }

    function testSearchResult()
    {
        $result_array = json_decode('{
  "idf": {
    "lol": 2.302585092994046
  },
  "query": {
    "query": [
      "lol"
    ],
    "offset": 0,
    "pagesize": 10
  },
  "transformedQuery": {
    "query": [
      "lol"
    ]
  },
  "totalHits": 20,
  "facets": {
    "categories": {
      "images": 1,
      "funny": 1,
      "dog": 1,
      "random": 1
    },
    "random_field": {
        "useless":5
    }
  },
  "hits": [
    {
      "matchedTerms": {
        "lol": {
          "body": "0.5",
          "title": "1"
        }
      },
      "document": {
        "title": "Lol Dog",
        "categories": [
          "images",
          "funny",
          "dog"
        ],
        "body": "dog dog lol!!"
      },
      "id": "1",
      "score": 3.453877639491069
    },
    {
      "matchedTerms": {
        "lol": {
          "body": "1"
        }
      },
      "document": {
        "title": "Whatever",
        "categories": [
          "random"
        ],
        "body": "cat /dev/urandom... lol"
      },
      "id": "2",
      "score": 2.302585092994046
    }
  ]
}', true);
        $result = new Result($result_array);

        $this->assertEqual($result->count(),2);
        $this->assertEqual(count($result->getHits()), 2);
        $this->assertEqual(count($result->getFacets()), 1);
        $this->assertEqual($result->getTotalHits(), 20);

        foreach($result as $hit)
        {
            $this->assertIsA($hit, 'vierbergenlars\Forage\SearchResult\Hit');
        }

        foreach($result->getFacets() as $facet)
        {
            $this->assertIsA($facet, 'vierbergenlars\Forage\SearchResult\Facet');
            $this->assertEqual($facet->getField(), 'categories');
            foreach($facet as $field => $number) {
                $this->assertTrue(is_string($field), 'The field of a facet should be string.');
                $this->assertTrue(is_integer($number), 'The number of a facet should be integer.');
                $this->assertEqual($number, 1); // They all happen to only occur once
            }
        }
    }
}