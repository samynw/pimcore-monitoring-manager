<?php

namespace MonitoringManagerBundle\Config;

use Doctrine\Common\Collections\ArrayCollection;
use MonitoringManagerBundle\ValueObject\Config\Entry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Exception\ParseException;

class MonitoringConfig
{
    public const CONFIG_FILENAME = 'monitoring-jobs.yml';

    /** @var Dao */
    private $dao;
    /** @var array $config */
    private $config;
    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * MonitoringConfig constructor.
     * @param Dao $dao
     */
    public function __construct(Dao $dao, LoggerInterface $logger)
    {
        $this->dao = $dao;
        $this->logger = $logger;
        $this->reload();
    }

    /**
     * Load the config from array
     */
    public function reload(): void
    {
        try {
            $this->config = $this->dao->load(self::CONFIG_FILENAME);
        } catch (ParseException $e) {
            $this->logger->warning(sprintf(
                'Could not read config file %s (%s), falling back to default value',
                self::CONFIG_FILENAME,
                $e->getMessage()
            ));

            $this->config = [];
        }
    }

    /**
     * Save config to resource
     */
    public function save(): void
    {
        $this->dao->save(self::CONFIG_FILENAME, $this->config);
        $this->reload();
    }

    /**
     * Get a list of jobs from the config file
     *
     * @return ArrayCollection<string, Entry>
     */
    public function getJobs(): ArrayCollection
    {
        $jobs = new ArrayCollection();

        // Early return if no jobs are found
        if (!\array_key_exists('jobs', $this->config)) {
            return $jobs;
        }

        // Create an entry for each job in the config file
        foreach ($this->config['jobs'] as $key => $settings) {
            $jobs->set($key, Entry::fromArray($key, $settings));
        }

        return $jobs;
    }

    /**
     * Set the list of jobs
     *
     * @param iterable<string, Entry> $jobs
     */
    public function setJobs(iterable $jobs): void
    {
        foreach ($jobs as $job) {
            if (!$job instanceof Entry) {
                continue;
            }

            $this->config['jobs'][$job->getId()] = $job->toArray();
        }
    }

    /**
     * Remove a job from the config list
     *
     * @param string $key
     */
    public function removeJob(string $key): void
    {
        if (\array_key_exists($key, $this->config['jobs'])) {
            unset($this->config['jobs'][$key]);
        }
    }

    /**
     * Create/Overwrite a job in the config list
     *
     * @param string $key
     * @param Entry $job
     */
    public function setJob(string $key, Entry $job): void
    {
        $this->config['jobs'][$key] = $job->toArray();
    }
}
