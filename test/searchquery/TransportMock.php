<?php

namespace test\searchquery;

use vierbergenlars\Forage\Transport\TransportInterface;

class TransportMock implements TransportInterface
{
    public $arguments = array();
    public $calls = 0;
    public function deleteDoc($docId) {

    }

    public function getIndexMetadata() {

    }

    public function indexBatch(array $documents, array $filter) {

    }

    public function search($query, array $searchFields = null, array $facets = null, array $filters = null, $offset = 0, $pagesize = 10, array $weight = null) {
        $this->arguments = func_get_args();
        $this->calls++;
        return $result_array = json_decode('{
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
    }
}
