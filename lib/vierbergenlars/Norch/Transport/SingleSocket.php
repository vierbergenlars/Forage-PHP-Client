<?php

namespace vierbergenlars\Norch\Transport;

/**
 * Uses a single socket for all communications with the Norch search server
 *
 * {@inheritDoc}
 */
class SingleSocket extends Socket
{
    /**
     * The opened socket
     * @var resource
     */
    protected $fd;

    /**
     * Creates a new single socket transport
     * @param string|resource $socket The path to a unix socket, or an open socket.
     */
    public function __construct($socket = '/run/norch.sock') {
        if(is_resource($socket))
            $this->fd = $socket;
        else
            parent::__construct($socket);
    }
    /**
     * Returns the open socket, or creates a new one
     * @return resource An open socket
     */
    protected function getSocket() {
        if($this->fd)
            return $this->fd;
        return $this->fd = parent::getSocket();
    }

    /**
     * Does _not_ close the socket
     * @param resource $sock
     */
    protected static function closeSock($sock) {

    }

    /**
     * Closes the socket
     */
    public function __destruct() {
        parent::closeSock($this->fd);
    }
}
