<?php

namespace test\searchresult;

use vierbergenlars\Norch\SearchResult\Facet as SearchFacet;

class facet extends \UnitTestCase
{
    function testFacet()
    {
        $facet = new SearchFacet('aa', array(
            'b'=>3,
            'x'=>80,
            'd'=>8
        ));

        $this->assertEqual($facet->count(), 3);
        $this->assertEqual($facet->getField(), 'aa');
        $this->assertEqual($facet->getResults(), array(
            'b'=>3,
            'x'=>80,
            'd'=>8
        ));

        $it = 0;
        foreach($facet as $value=>$number) {
            $this->assertTrue(is_string($value), 'The value of a facet should be string.');
            $this->assertTrue(is_integer($number), 'The number of a facet should be integer.');
            switch($it) {
                case 0:
                    $this->assertEqual($value, 'b');
                    $this->assertEqual($number, 3);
                    break;
                case 1:
                    $this->assertEqual($value, 'x');
                    $this->assertEqual($number, 80);
                    break;
                case 2:
                    $this->assertEqual($value, 'd');
                    $this->assertEqual($number, 8);
                    break;
            }
            $it++;
        }
    }
}
