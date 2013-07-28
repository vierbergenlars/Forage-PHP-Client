<?php

namespace test\searchindex;

use vierbergenlars\Norch\Transport\TransportInterface;

class TransportMock implements TransportInterface
{
    public $deleteArguments = array();
    public $deleteCalls = 0;
    public $indexArguments = array();
    public $indexCalls = 0;
    public function deleteDoc($docId) {
        $this->deleteArguments[] = $docId;
        $this->deleteCalls++;
        return true;
    }

    public function getIndexMetadata() {

    }

    public function indexBatch(array $documents, array $filter) {
        $this->indexArguments = func_get_args();
        $this->indexCalls++;
        return true;
    }

    public function search($query, array $searchFields = null, array $facets = null, array $filters = null, $offset = 0, $pagesize = 10, array $weight = null) {

    }
}
