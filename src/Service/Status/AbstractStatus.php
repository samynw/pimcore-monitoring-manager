<?php

namespace MonitoringManagerBundle\Service\Status;

abstract class AbstractStatus implements StatusInterface
{
    private $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }
}
