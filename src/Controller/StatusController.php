<?php

namespace MonitoringManagerBundle\Controller;

use MonitoringManagerBundle\Service\Jobs\JobInterface;
use MonitoringManagerBundle\Service\Listing\EnabledJobs;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends FrontendController
{
    /** @var EnabledJobs */
    private $enabledJobs;

    /**
     * StatusController constructor.
     * @param EnabledJobs $enabledJobs
     */
    public function __construct(EnabledJobs $enabledJobs)
    {
        $this->enabledJobs = $enabledJobs;
    }

    /**
     * Fetch the monitoring task by it's job ID,
     * run the validating tasks
     * and return the status code
     *
     * @Route("/monitoring/status/{job}", methods={"GET","HEAD"}, name="monitoring_jobstatus")
     * @param string $job
     * @return Response
     */
    public function jobStatus(string $job): Response
    {
        try {
            $task = $this->enabledJobs->getJobById($job);
            if (!$task->isEnabled()) {
                throw new \InvalidArgumentException('No valid job found');
            }

            /** @var JobInterface $job */
            $jobInstance = $this->container->get($task->getClass());
            if (!($jobInstance instanceof JobInterface)) {
                throw new \InvalidArgumentException('No valid job found');
            }

            $status = $jobInstance->run();
            return new Response(null, $status->getHttpStatusCode());
        } catch (\InvalidArgumentException $e) {
            // No active job found with the given argument
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
    }
}
