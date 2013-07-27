Norch-PHP-Client
================

A PHP client for the Norch search server

License: [MIT](https://github.com/vierbergenlars/Norch-PHP-Client/blob/master/LICENSE)

## Installation

`$ composer require vierbergenlars/norch-client:*`

## Usage

### Transport library

```php
<?php

use vierbergenlars\Norch\Transport\Http as HttpTransport;

$transport = new HttpTransport('http://localhost:3000/'); // Edit if the Norch server lives somewhere else

```

#### Indexing

```php
<?php

// Index a bunch of documents
$transport->indexBatch(
    array( // Documents to index (the array keys should be unique for each document on the server)
        785 => array(
            'title' => 'My super-awesome report',
            'body' => 'Bla bla bla',
            'tags' => array('awesome', 'financial'),
            'countries' => array('USA', 'Canada', 'Mexico')
        ),
        786 => array(
            'title' => 'Another super-awesome report',
            'body' => 'Bla bla bla**2',
            'tags' => array('awesome', 'advice'),
            'countries' => array('Brazil', 'Mexico');
        )
    ),
    array( // Fields to build facets for (must contain arrays)
        'tags',
        'countries'
    )
);
```

#### Deleting

```php
<?php
// Delete a document
$transport->deleteDoc(786);
```

#### Searching

```php
<?php
// Search
$results = $transport->search(
    'Bla bla report',
    array('title','body'), // Fields to search in
    array('tags', 'countries'), // Facets to calculate
    array( // Only search documents with fields that are equal to these values
        'countries'=> array('USA','Mexico'), // Countries should be either USA or Mexico
    ),
    5, // Skip the first 5 results (for pagination)
    5, // Return the 5 next items (for pagination)
    array( // Weigh these fields more than the others (by default, all fields are weighed 1)
        'title'=> array(3) // Finding the search in the title grants 3 times more points than finding it elsewhere
    )
);

$results['totalHits'];

$results['facets'] ==
    array(
        'tags'=> array(
            'awesome'=>2,
            'advice'=>1,
            'financial'=>1
        ),
        'countries'=> array(
           'USA'=>1,
           'Canada'=>1,
           'Mexico'=>2,
           'Brazil'=>1
        )
    );

$results['hits'] ==
    array(
        array(
            'matchedTerms'=> array(
                'report'=> array(
                    'title'=>1
                ),
                'bla'=> array(
                    'body'=>2
                )
            ),
            'document'=> array(
                'title' => 'My super-awesome report',
                'body' => 'Bla bla bla',
                'tags' => array('awesome', 'financial'),
                'countries' => array('USA', 'Canada', 'Mexico'),
                'id'=>785
            ),
            'score'=> 3.119162312519754
        ),
        // ... Repeated for each result
    );

```

#### Metadata

```php
<?php

// Get metadata about the state of the index
$metadata = $transport->getIndexMetadata();

$metadata['totalIndexedFields'];
$metadata['totalDocs'];
$metadata['reverseIndexSize'];

$metadata['indexedFieldNames'] ==
    array(
        'title',
        'body',
        'tags',
        'countries'
    );

$metadata['availableFacets'] ==
    array(
        'tags',
        'countries'
    );
```
