<?php

namespace MonitoringManagerBundle\Service\Listing;

use Doctrine\Common\Collections\ArrayCollection;
use MonitoringManagerBundle\Service\Jobs\JobInterface;

class AvailableJobs
{
    /** @var ArrayCollection */
    private $jobs;

    /**
     * AvailableJobs constructor.
     *
     * @param iterable $jobs List of services tagged as "monitoring_manager.job"
     */
    public function __construct(iterable $jobs)
    {
        $this->jobs = new ArrayCollection();
        $this->init($jobs);
    }

    /**
     * Loop all tagged services and add to collection if possible
     *
     * @param iterable $jobs
     */
    private function init(iterable $jobs)
    {
        foreach ($jobs as $job) {
            if ($job instanceof JobInterface) {
                $this->jobs->set(\get_class($job), $job);
            }
        }
    }

    /**
     * Return a collection of jobs
     *
     * @return ArrayCollection
     */
    public function getJobs(): ArrayCollection
    {
        return $this->jobs;
    }
}
