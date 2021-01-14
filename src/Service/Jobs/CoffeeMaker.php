<?php

namespace Samynw\MonitoringManagerBundle\Service\Jobs;

use Samynw\MonitoringManagerBundle\Service\Status\StatusInterface;
use Samynw\MonitoringManagerBundle\Service\Status\Teapot;

class CoffeeMaker implements JobInterface
{
    /**
     * @return string
     */
    public function getLabel(): string
    {
        return 'Coffee Maker';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Simply returns the Teapot status';
    }

    /**
     * @return StatusInterface
     */
    public function run(): StatusInterface
    {
        return new Teapot();
    }
}
