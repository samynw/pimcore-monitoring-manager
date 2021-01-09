<?php

namespace MonitoringManagerBundle\Command;

use MonitoringManagerBundle\Service\Jobs\JobInterface;
use MonitoringManagerBundle\Service\Listing\EnabledJobs;
use MonitoringManagerBundle\Service\Status\StatusInterface;
use MonitoringManagerBundle\Service\Status\Success;
use MonitoringManagerBundle\ValueObject\Config\Entry;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Routing\RouterInterface;

class JobDetailsCommand extends AbstractCommand implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var EnabledJobs */
    private $enabledJobs;
    /** @var RouterInterface */
    private $router;

    public function __construct(EnabledJobs $enabledJobs, RouterInterface $router)
    {
        parent::__construct();

        $this->enabledJobs = $enabledJobs;
        $this->router = $router;
    }

    protected function configure()
    {
        $this
            ->setName('monitoring:job:details')
            ->setDescription('Show details of a monitoring job')
            ->addArgument('job', InputArgument::REQUIRED, 'key of the monitoring task');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $job = $this->getJob();
        $status = $job->run();

        $this->printJobDetails($job);
        $this->printStatusDetails($status);

        return $status->getConsoleExitCode();
    }

    /**
     * Try to fetch the correct monitoring task
     *
     * @return JobInterface
     */
    private function getJob(): JobInterface
    {
        $jobName = $this->input->getArgument('job');
        try {
            $job = $this->enabledJobs->getJobById($jobName);
        } catch (\InvalidArgumentException $e) {
            $job = $this->enabledJobs->getJobByClassName($jobName);
        }

        if (!$job instanceof Entry || !$job->isEnabled()) {
            throw new \InvalidArgumentException(
                sprintf("No matching job found for '%s'", $jobName)
            );
        }

        $jobInstance = $this->container->get($job->getClass());
        if (!($jobInstance instanceof JobInterface)) {
            throw new \InvalidArgumentException('No valid job found');
        }

        return $jobInstance;
    }

    /**
     * Write the job details to console output
     *
     * @param JobInterface $job
     */
    private function printJobDetails(JobInterface $job): void
    {
        $this->io->title($job->getLabel());
        $this->io->writeln($job->getDescription());
        $this->io->section('Job details');
        $this->io->definitionList(
            ['Endpoint' => $this->router->generate('monitoring_jobstatus', ['job' => $this->input->getArgument('job')])],
            ['Class' => \get_class($job)]
        );
    }

    /**
     * Write status details to console output
     *
     * @param StatusInterface $status
     */
    private function printStatusDetails(StatusInterface $status): void
    {
        $this->io->section('Status details');
        $this->io->definitionList(
            ['Status' => $status->getStatus()],
            ['HTTP status code' => $status->getHttpStatusCode()],
            ['Console exit code' => $status->getConsoleExitCode()],
            ['Class' => \get_class($status)]
        );

        if($status instanceof Success){
            $this->io->success('Status check passed');
        }else{
            $this->io->error('Status check failed');
        }
    }
}
