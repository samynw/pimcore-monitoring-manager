<?php

namespace Samynw\MonitoringManagerBundle\Service\Status;

interface StatusInterface
{
    public const SUCCESS = 'success';
    public const FAILURE = 'failure';

    /**
     * String representation of the status
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Get (optional) data with extra details
     *
     * @return mixed
     */
    public function getData();

    /**
     * Set (optional) data with extra details
     *
     * @param $data
     */
    public function setData($data);

    /**
     * The code the JSON response should use
     *
     * @return int
     */
    public function getHttpStatusCode(): int;

    /**
     * The command exit code that should be returned in CLI
     *
     * @return int
     */
    public function getConsoleExitCode(): int;
}
