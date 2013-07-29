<?php

namespace test;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/vierbergenlars/simpletest/autorun.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('test', __DIR__.'/..');
$loader->register();

class NorchClientTests extends \TestSuite
{
    public function __construct()
    {
        parent::__construct('Norch Client tests');
        $this->add(new transport\http);
        $this->add(new searchresult\hit);
        $this->add(new searchresult\facet);
        $this->add(new searchresult\searchresult);
        $this->add(new searchquery\query);
        $this->add(new searchquery\querybuilder);
        $this->add(new searchquery\transportawarequery);
        $this->add(new searchindex\index);
        $this->add(new searchindex\transportawareindex);
        $this->add(new client);
    }
}

class NorchODMTests extends \TestSuite
{
    public function __construct() {
        parent::__construct('Norch ODM tests');
        $this->add(new odm\searchhit);
        $this->add(new odm\searchresult);
        $this->add(new odm\searchquery);
        $this->add(new odm\searchindex);
        $this->add(new odm\documentmapper);
    }
}
