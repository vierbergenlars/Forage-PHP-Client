<?php

namespace test;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/simpletest/simpletest/autorun.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('test', __DIR__.'/..');
$loader->register();

class ForageClientTests extends \TestSuite
{
    public function __construct()
    {
        parent::__construct('Forage Client tests');
        if (!getenv('CI'))
            $this->add(new transport\http);
        $this->add(new searchresult\hit);
        $this->add(new searchresult\facet);
        $this->add(new searchresult\searchresult);
        $this->add(new searchquery\query);
        $this->add(new searchquery\querybuilder);
        $this->add(new searchindex\index);
        $this->add(new client);
    }
}

class ForageODMTests extends \TestSuite
{
    public function __construct() {
        parent::__construct('Forage ODM tests');
        $this->add(new odm\searchhit);
        $this->add(new odm\searchresult);
        $this->add(new odm\searchquery);
        $this->add(new odm\searchindex);
        $this->add(new odm\documentmapper);
    }
}

class ForageQueryParserTests extends \TestSuite
{

    public function __construct()
    {
        parent::__construct('Forage QueryParser tests');
        $this->add(new queryparser\lexer);
        $this->add(new queryparser\compiler);
    }

}
