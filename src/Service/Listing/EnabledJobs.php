<?php

namespace MonitoringManagerBundle\Service\Listing;

use Doctrine\Common\Collections\ArrayCollection;
use MonitoringManagerBundle\Config\MonitoringConfig;
use MonitoringManagerBundle\ValueObject\Config\Entry;

class EnabledJobs
{
    /** @var ArrayCollection<string, Entry> $jobs */
    private $jobs;

    /**
     * EnabledJobs constructor.
     *
     * @param MonitoringConfig $config
     */
    public function __construct(MonitoringConfig $config)
    {
        $this->jobs = $config->getJobs();
    }

    /**
     * @return ArrayCollection<string, Entry>
     */
    public function getJobs(): ArrayCollection
    {
        return $this->jobs;
    }

    /**
     * Sort the jobs by key before returning
     *
     * @return ArrayCollection<string, Entry>
     * @throws \Exception
     */
    public function getJobsSortedByKey(): ArrayCollection
    {
        $iterator = $this->getJobs()->getIterator();
        $iterator->ksort();
        $this->jobs = new ArrayCollection(\iterator_to_array($iterator));

        return $this->jobs;
    }

    /**
     * Get a job by its (string) ID
     *
     * @param string $id
     * @return Entry
     */
    public function getJobById(string $id): Entry
    {
        if ($this->jobs->containsKey($id)) {
            return $this->jobs->get($id);
        }

        throw new \InvalidArgumentException(
            sprintf('No job enabled with id "%s".', $id)
        );
    }

    /**
     * Get a job by its FQCN
     *
     * Warning: this will only return the first result
     * You probably shouldn't enable a job multiple times
     *
     * @param string $class FQCN of the job class
     * @return Entry
     */
    public function getJobByClassName(string $class): Entry
    {
        $job = $this->jobs->filter(
            function (Entry $entry) use ($class) {
                return ($entry->getClass() === $class);
            }
        );

        if ($job->count() > 0) {
            return $job->first();
        }

        throw new \InvalidArgumentException(
            sprintf('No job enabled with class "%s".', $class)
        );
    }
}
