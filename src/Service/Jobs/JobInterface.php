<?php

namespace MonitoringManagerBundle\Service\Jobs;

use MonitoringManagerBundle\Service\Status\StatusInterface;

interface JobInterface
{
    /**
     * Get the nice name of the job
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get the job description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Run the job and return a Status
     *
     * @return StatusInterface
     */
    public function run(): StatusInterface;
}
