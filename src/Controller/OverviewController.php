<?php

namespace MonitoringManagerBundle\Controller;

use MonitoringManagerBundle\Model\View\Task;
use MonitoringManagerBundle\Service\Listing\EnabledJobs;
use MonitoringManagerBundle\ValueObject\Config\Entry;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class OverviewController extends FrontendController
{
    /** @var EnabledJobs */
    private $enabledJobs;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * OverviewController constructor.
     *
     * @param EnabledJobs $enabledJobs
     */
    public function __construct(EnabledJobs $enabledJobs, RouterInterface $router)
    {
        $this->enabledJobs = $enabledJobs;
        $this->router = $router;
    }

    /**
     * Get an overview of all activated jobs and their current status
     *
     * @Route("/monitoring/overview", methods={"GET"}, name="monitoring_overview")
     * @return Response
     */
    public function overview(): Response
    {
        /** @var Task[] $jobs */
        $jobs = [];

        /** @var Entry $entry */
        foreach ($this->enabledJobs->getJobsSortedByKey() as $key => $entry) {
            $jobs[$key] = new Task(
                $jobInstance = $this->container->get($entry->getClass()),
                $key,
                $entry->isEnabled(),
                $this->router->generate('monitoring_jobstatus', ['job' => $key])
            );
        }

        return $this->render('@MonitoringManager/overview/overview.html.twig', [
            'jobs' => $jobs,
        ]);
    }
}
