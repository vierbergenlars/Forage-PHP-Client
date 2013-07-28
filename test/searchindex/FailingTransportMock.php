<?php

namespace test\searchindex;

class FailingTransportMock extends TransportMock
{
    function deleteDoc($docId)
    {
        parent::deleteDoc($docId);
        return false;
    }

    function indexBatch(array $documents, array $filter)
    {
        parent::indexBatch($documents, $filter);
        return false;
    }
}
