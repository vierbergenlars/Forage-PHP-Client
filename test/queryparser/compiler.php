<?php

namespace test\queryparser;

use vierbergenlars\Norch\QueryParser\Compiler as C;
use vierbergenlars\Norch\SearchQuery\Query;
use vierbergenlars\Norch\SearchQuery\QueryBuilder;
use vierbergenlars\Norch\QueryParser\ParseException;
use vierbergenlars\Norch\QueryParser\Token;
use test\searchquery\TransportMock;

class compiler extends \UnitTestCase
{
    function testCompiler()
    {
        $transport = new TransportMock;
        $query = new Query($transport);
        $queryBuilder = new QueryBuilder($query);
        $compiler = new C($queryBuilder);

        $c = clone $compiler;
        $ex = false;
        try {
            $c->compileQuery('field: field2:');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $c = clone $compiler;
        $ex = false;
        try {
            $c->compileQuery('field:');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $c = clone $compiler;
        $ex = false;
        try {
            $c->compileQuery('field: @field2');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $c = clone $compiler;
        $query = $c->compileQuery('sofsq field3^2 field: aaa @field2 "sfosf\" sfsf"')
                ->getQuery();
        $this->assertEqual($query->query, 'sofsq sfosf" sfsf');
        $this->assertEqual($query->facetFields, array());
        $this->assertEqual($query->searchFields, array('field2'));
        $this->assertEqual($query->searchFilters, array('field' => array('aaa')));
        $this->assertEqual($query->weights, array('field3' => array(2)));
    }

    function testCompilerRestrictions()
    {
        $transport = new TransportMock;
        $query = new Query($transport);
        $queryBuilder = new QueryBuilder($query);
        $compiler = new C($queryBuilder);

        $c = clone $compiler;
        $c->setAllowedTokens(array(Token::T_FIELD_SEARCH, Token::T_STRING));
        $ex = false;
        try {
            $c->compileQuery('field: sofnsof @sfons');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $c = clone $compiler;
        $c->setAllowedTokens(array(Token::T_FIELD_NAME, Token::T_FIELD_VALUE));
        $c->compileQuery('field2: sfsf');

        $c = clone $compiler;
        $c->setAllowedSearchFields(array('field2', 'field3'));
        $ex = false;
        try {
            $c->compileQuery('@field sfnoqsfn');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $c = clone $compiler;
        $c->setAllowedSearchFields(array('field', 'field3'));
        $c->compileQuery('@field field2: sfsf');

        $c = clone $compiler;
        $c->setAllowedFieldNames(array('field2', 'field3'));
        $ex = false;
        try {
            $c->compileQuery('field: onsqfon');
        } catch(ParseException $e) {
            $ex = true;
        }
        $this->assertTrue($ex);

        $c = clone $compiler;
        $c->setAllowedFieldNames(array('field2', 'field3'));
        $c->compileQuery('@field field2: sfsf');
    }

}
