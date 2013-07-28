<?php

namespace vierbergenlars\Norch\SearchIndex;

use vierbergenlars\Norch\Transport\TransportInterface;

/**
 * An index that already knows a transport
 */
class TransportAwareIndex extends Index
{
    /**
     * The transport to use
     * @var \vierbergenlars\Norch\Transport\TransportInterface
     */
    protected $transport;

    /**
     * Creates a new index with a transport
     * @param \vierbergenlars\Norch\Transport\TransportInterface $transport
     */
    public function __construct(TransportInterface $transport) {
        $this->transport = $transport;
        parent::__construct();
    }

    /**
     * Flushes the changes
     * @return bool
     */
    public function flush()
    {
        return parent::flush($this->transport);
    }
}
