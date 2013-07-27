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
    }
}
