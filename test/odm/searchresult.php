<?php

namespace test\odm;

use vierbergenlars\Forage\ODM\SearchResult as Result;
use vierbergenlars\Forage\ODM\HydrationSettings\SingleObjectHydration;

class searchresult extends \UnitTestCase
{
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
        $hydationSettings = new SingleObjectHydration(__NAMESPACE__ . '\DocumentObject');

        $result->hydrate($hydationSettings);

        foreach($result as $hit)
        {
            $this->assertIsA($hit, 'vierbergenlars\Forage\ODM\SearchHit');
            $this->assertIsA($hit->getDocument(), __NAMESPACE__.'\DocumentObject');
        }
    }
}
