<?php

namespace test\odm;

use vierbergenlars\Forage\ODM\Indexable;

class DocumentObject implements Indexable
{
    protected $id;

    protected $title;

    protected $body;

    protected $categories;

    public function __construct($title, $body, array $categories)
    {
        $this->id = uniqid();
        $this->title = $title;
        $this->body = $body;
        $this->categories = new \ArrayObject($categories);
    }

    public function getIdReal()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function toDocument() {
        return array (
            'id' => $this->id,
            'title'=>  $this->title,
            'body'=>  $this->body,
            'categories'=>  $this->categories->getArrayCopy()
        );
    }
}
