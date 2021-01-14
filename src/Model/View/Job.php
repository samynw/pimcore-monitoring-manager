<?php

namespace Samynw\MonitoringManagerBundle\Model\View;

use Samynw\MonitoringManagerBundle\Service\Jobs\JobInterface;
use Pimcore\Tool;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class Job
 * View model for easy use in Twig template
 *
 * @package Samynw\MonitoringManagerBundle\Model\View
 */
class Task
{
    /** @var JobInterface */
    private $job;
    /** @var bool $active */
    private $active;
    /** @var string */
    private $endpoint;
    /** @var string */
    private $key;

    /**
     * Job constructor.
     *
     * @param JobInterface $job
     * @param bool $active
     * @param string $uri
     */
    public function __construct(JobInterface $job, string $key, bool $active, string $uri)
    {
        $this->job = $job;
        $this->key = $key;
        $this->active = $active;
        $this->endpoint = $uri;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->job->getLabel();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->job->getDescription();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return Tool::getHostUrl() . $this->endpoint;
    }
}
