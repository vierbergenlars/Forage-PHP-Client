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
    private $basePath;

    /**
     * Creates a new HTTP transport layer
     * @param string $base_path The base path of the Forage server
     */
    public function __construct($basePath = 'http://localhost:3000/')
    {
        $this->basePath = $basePath;
    }

    private static function addPostBody($curl, $fields = array(), $files = array())
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

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: multipart/form-data; boundary='.$boundary,
            'Content-Length: '.strlen($data)
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
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
        $jsonDocuments = json_encode($documents);
        $docid = uniqid();
        $curl = curl_init($this->basePath.'indexer');
        if(!$curl)
            throw new TransportException('Cannot open a cURL session');
        self::addPostBody($curl, array(
            'filterOn'=>implode(',', $filter)
            ), array(
                'document'=> array(
                    'filename'=>$docid.'.json',
                    'data'=> $jsonDocuments
                )
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $reply = curl_exec($curl);
        if(curl_errno($curl))
            throw new TransportException('cURL error: '.curl_error($curl), curl_errno($curl));
        curl_close($curl);
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
        array $searchFields = array(),
        array $facets       = array(),
        array $filters      = array(),
              $offset       = 0,
              $pagesize     = 10,
        array $weight       = array()
    )
    {
        $getParameters = array(
            'q'=>(string)$query,
            'searchFields'=>$searchFields,
            'facets'=>implode(',', $facets),
            'filters'=>$filters,
            'offset'=>(int)$offset,
            'pagesize'=>(int)$pagesize,
            'weight'=>$weight,
        );

        $curl = curl_init($this->basePath.'search?'.http_build_query($getParameters));
        if(!$curl)
            throw new TransportException('Cannot open a cURL session');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($curl);
        if(curl_errno($curl))
            throw new TransportException('cURL error: '.curl_error($curl), curl_errno($curl));
        curl_close($curl);
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
        $curl = curl_init($this->basePath.'delete');
        if(!$curl)
            throw new TransportException('Cannot open a cURL session');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'docID'=>$docId
        ));
        $resp = curl_exec($curl);
        if(curl_errno($curl))
            throw new TransportException('cURL error: '.curl_error($curl), curl_errno($curl));
        curl_close($curl);
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
        $ch = curl_init($this->basePath.'indexData');
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
