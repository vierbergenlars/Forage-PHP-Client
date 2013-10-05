<?php

namespace vierbergenlars\Forage\Transport;

/**
 * HTTP as transport layer for Forage queries
 *
 * {@inheritDoc}
 */
class Http implements TransportInterface
{
    /**
     * The path to the place where Forage is listening
     * @var string
     */
    private $base_path;

    /**
     * Creates a new HTTP transport layer
     * @param string $base_path The base path of the Forage server
     */
    public function __construct($base_path = 'http://localhost:3000/')
    {
        $this->base_path = $base_path;
    }

    private static function addPostBody($ch, $fields = array(), $files = array())
    {
        $boundary = '--------------'.uniqid();

        $data = '';

        foreach($fields as $name=>$value) {
            $data.='--'.$boundary."\r\n";
            $data.='Content-Disposition: form-data; name="'.$name.'"';
            $data.="\r\n\r\n";
            $data.=$value;
            $data.="\r\n";
        }

        foreach($files as $name=>$file) {
            $data.='--'.$boundary."\r\n";
            $data.='Content-Disposition: form-data; name="'.$name.'"; filename="'.$file['filename'].'"'."\r\n";
            $data.="Content-Type: application/octet-stream\r\n\r\n";
            $data.=$file['data'];
            $data.="\r\n";
        }

        $data.='--'.$boundary.'--'."\r\n";

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: multipart/form-data; boundary='.$boundary,
            'Content-Length: '.strlen($data)
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    /**
     * Adds a set of documents to the index
     * @param array $documents An array of documents to submit. Each document is an array. The document may not have a key named 'id'.
     * @param array $filter Array of fields that can be used for faceted search. Can only contain fields that are arrays in the document.
     * @return boolean
     * @throws TransportException
     */
    public function indexBatch(array $documents, array $filter)
    {
        $json_docs = json_encode($documents);
        $docid = uniqid();
        $ch = curl_init($this->base_path.'indexer');
        if(!$ch)
            throw new TransportException('Cannot open a cURL session');
        self::addPostBody($ch, array(
            'filterOn'=>implode(',', $filter)
            ), array(
                'document'=> array(
                    'filename'=>$docid.'.json',
                    'data'=> $json_docs
                )
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reply = curl_exec($ch);
        if(curl_errno($ch))
            throw new TransportException('cURL error: '.curl_error($ch), curl_errno($ch));
        curl_close($ch);
        if(trim($reply) === 'indexed batch: '.$docid.'.json')
            return true;
        return false;
    }

    /**
     * Perform a search query
     *
     * @param string $query The search query
     * @param array $searchFields An array of fields to search in
     * @param array $facets An array of fields to facet on
     * @param array $filters Limit search to fields with a particular value(s). Each field contains an array of acceptable values.
     * @param int $offset The offset to start in the result set
     * @param int $pagesize The size of each page
     * @param array $weight The weights to give each field.
     * @return array Raw json decoded data from the search server
     * @throws TransportException
     */
    public function search(
              $query,
        array $searchFields = null,
        array $facets       = null,
        array $filters      = null,
              $offset       = 0,
              $pagesize     = 10,
        array $weight       = null
    )
    {
        $querystring = '?q=' . urlencode($query);
        if($searchFields) {
            foreach($searchFields as $field)
                $querystring.='&searchFields[]=' . urlencode($field);
        }
        if($facets) {
            $querystring.='&facets=' . urlencode(implode(',', $facets));
        }
        if($filters) {
            foreach($filters as $name=>$values)
                foreach($values as $value)
                    $querystring.='&filter[' . urlencode($name) . '][]=' . urlencode($value);
        }
        if($offset) {
            $querystring .= '&offset=' . urlencode($offset);
        }
        if($pagesize != 10) {
            $querystring .= '&pagesize=' . urlencode($pagesize);
        }
        if($weight) {
            foreach($weight as $name=>$values)
                foreach($values as $value)
                    $querystring.='&weight[' . urlencode($name) . '][]=' . urlencode($value);
        }


        $ch = curl_init($this->base_path.'search'.$querystring);
        if(!$ch)
            throw new TransportException('Cannot open a cURL session');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        if(curl_errno($ch))
            throw new TransportException('cURL error: '.curl_error($ch), curl_errno($ch));
        curl_close($ch);
        if($resp === 'no results') {
            return array(
                'totalHits'=>0,
                'facets'=>array(),
                'hits'=> array(),
            );
        }
        return json_decode($resp, true);
    }

    /**
     * Removes a document with a specific ID
     *
     * @param int $docId
     * @return boolean
     * @throws TransportException
     */
    public function deleteDoc($docId)
    {
        $ch = curl_init($this->base_path.'delete');
        if(!$ch)
            throw new TransportException('Cannot open a cURL session');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'docID'=>$docId
        ));
        $resp = curl_exec($ch);
        if(curl_errno($ch))
            throw new TransportException('cURL error: '.curl_error($ch), curl_errno($ch));
        curl_close($ch);
        if(trim($resp) === 'deleted '.$docId)
            return true;
        return false;
    }

    /**
     * Retrieve meta-data about the index
     *
     * @return array The raw JSON decoded data from the search server
     * @throws TransportException
     */
    public function getIndexMetadata()
    {
        $ch = curl_init($this->base_path.'indexData');
        if(!$ch)
            throw new TransportException('Cannot open a cURL session');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        if(curl_errno($ch))
            throw new TransportException('cURL error: '.curl_error($ch), curl_errno($ch));
        curl_close($ch);

        return json_decode($resp, true);
    }

}
