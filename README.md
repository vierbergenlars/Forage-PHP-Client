Forage-PHP-Client
================

[![Build Status](https://travis-ci.org/vierbergenlars/Forage-PHP-Client.png?branch=master)](https://travis-ci.org/vierbergenlars/Forage-PHP-Client)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/vierbergenlars/Forage-PHP-Client/badges/quality-score.png?s=de9b3c08fd559763d8b7e896d463dd65d9fc663e)](https://scrutinizer-ci.com/g/vierbergenlars/Forage-PHP-Client/)
[![Latest Stable Version](https://poser.pugx.org/vierbergenlars/forage-client/v/stable.png)](https://packagist.org/packages/vierbergenlars/forage-client)
[![Total Downloads](https://poser.pugx.org/vierbergenlars/forage-client/downloads.png)](https://packagist.org/packages/vierbergenlars/forage-client)

A PHP client for the Forage search server

License: [MIT](https://github.com/vierbergenlars/Forage-PHP-Client/blob/master/LICENSE)

## Installation

`$ composer require vierbergenlars/forage-client:~0.2@alpha`

## Usage example

```php
<?php

use vierbergenlars\Forage\Transport\Http as HttpTransport;
use vierbergenlars\Forage\Client;

$transport = new HttpTransport;
$client = new Client($transport);

$query = $client->createQueryBuilder()
               ->setSearchQuery('Funny cat')
               ->setOffset(($_GET['page']-1)*10)
               ->setLimit(10)
               ->addSearchField('title')
               ->addFacet('media_type')
               ->addFilter('animal', 'cat')
               ->addFilter('categories', array('funny', 'lol'))
               ->addWeight('title', 3)
               ->getQuery();

$results = $query->execute();

echo 'Total hits: '.$results->getTotalHits();
foreach($result as $hit) {
    /* @var $hit \vierbergenlars\Forage\SearchResult\Hit */
    echo ' - '.$hit->getDocument()['title'].' (score: '.$hit->getScore().'; id='.$hit->getId().')';
}
```

Full documentation is available in the [wiki](https://github.com/vierbergenlars/Forage-PHP-Client/wiki/_pages), or have a look at the [API documentation](http://vierbergenlars.github.io/Forage-PHP-Client/)
