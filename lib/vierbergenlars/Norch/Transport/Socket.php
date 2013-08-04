<?php

namespace vierbergenlars\Norch\Transport;

/**
 * A socket as transport layer for Norch queries
 *
 * {@inheritDoc}
 */
class Socket implements TransportInterface
{
    /**
     * The location of the norch socket, or the open socket itself
     * @var string
     */
    protected $socket;

    /**
     * Creates a new socket transport
     * @param string $socket The location of the norch socket
     */
    public function __construct($socket = '/run/norch.sock')
    {
        $this->socket = 'unix://'.$socket;
    }

    /**
     * Reads from socket until NUL
     * @param resource $sock The socket to read from
     * @return string The data that was read
     * @throws TransportException
     */
    private static function readSock($sock)
    {
        $allData='';
        do {
            if(feof($sock)) throw new TransportException('The socket ended unexpectedly');
            $data = fread($sock, 1024);
            if($data === false) throw new TransportException('Cannot read from socket');
            $allData.=$data;
        } while(substr($allData, -1) != chr(0));
        if($allData === '') throw new TransportException('No data');
        return substr($allData, 0, -1);
    }

    /**
     * Writes to socket
     * @param resource $sock The socket to write to
     * @param string $data The data to write
     * @throws TransportException
     */
    private static function writeSock($sock, $data)
    {
        if(false === fwrite($sock, $data.chr(0))) throw new TransportException('Cannot write to socket');
    }

    /**
     * Creates a new socket
     * @return resource A socket
     * @throws TransportException
     */
    protected function getSocket()
    {
        $sock = fsockopen($this->socket);
        if(!$sock) throw new TransportException('Cannot open the socket');
        return $sock;
    }

    /**
     * Closes a socket
     * @param resource $sock The socket to close
     */
    protected static function closeSock($sock)
    {
        fclose($sock);
    }

    public function deleteDoc($docId) {
        $sock = $this->getSocket();
        self::writeSock($sock, '{"exec:"delete", "docID":"'+$docId+'"}');
        $ret = trim(self::readSock($sock));
        self::closeSock($sock);
        var_dump($ret);
        if($ret === 'deleted '.$docId)
            return true;
        return false;
    }

    public function getIndexMetadata() {
        $sock = $this->getSocket();
        self::writeSock($sock, '{"exec":"indexData"}');
        $ret = self::readSock($sock);
        self::closeSock($sock);
        return json_decode($ret, true);
    }

    public function indexBatch(array $documents, array $filter) {
        $sock = $this->getSocket();
        $data = array();
        $data['exec'] = 'index';
        $data['document'] = json_encode($documents);
        $data['document_name'] = uniqid().'.json';
        $data['filters'] = $filter;
        $serialized = json_encode($data);
        self::writeSock($sock, $serialized);
        $ret = trim(self::readSock($sock));
        self::closeSock($sock);
        if($ret == 'indexed batch: '.$data['document_name'])
            return true;
        return false;

    }

    public function search($query, array $searchFields = null, array $facets = null, array $filters = null, $offset = 0, $pagesize = 10, array $weight = null) {
        $data = array();
        $data['query'] = explode(' ', strtolower($query));
        if($searchFields) {
            $data['searchFields'] = $searchFields;
        }
        if($offset) {
            $data['offset'] = $offset;
        } else {
            $data['offset'] = 0;
        }
        if($pagesize) {
            $data['pagesize'] = $pagesize;
        } else {
            $data['pagesize'] = 10;
        }
        if($facets) {
            $data['facets'] = array_map(function($facet) {
                return strtolower($facet);
            }, $facets);
        }
        if($weight) {
            $data['weight'] = $weight;
        }
        if($filters) {
            $data['filters'] = $filters;
        }

        $serialized = json_encode(array('exec'=>'search','query'=>$data));
        $sock = $this->getSocket();
        self::writeSock($sock, $serialized);
        $ret = self::readSock($sock);
        self::closeSock($sock);
        return json_decode($ret, true);
    }

}
