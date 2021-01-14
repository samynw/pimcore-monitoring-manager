<?php

namespace Samynw\MonitoringManagerBundle\Command;

use Samynw\MonitoringManagerBundle\Service\Listing\AvailableJobs;
use Samynw\MonitoringManagerBundle\Service\Listing\EnabledJobs;
use Samynw\MonitoringManagerBundle\Service\Status\Failure;
use Samynw\MonitoringManagerBundle\Service\Status\StatusInterface;
use Samynw\MonitoringManagerBundle\Service\Status\Success;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

class ListCommand extends AbstractCommand
{
    /** @var AvailableJobs */
    private $availableJobs;
    /** @var EnabledJobs */
    private $enabledJobs;
    /** @var RouterInterface */
    private $router;

    /**
     * ListCommand constructor.
     *
     * @param AvailableJobs $availableJobs
     * @param EnabledJobs $enabledJobs
     * @param RouterInterface $router
     */
    public function __construct(AvailableJobs $availableJobs, EnabledJobs $enabledJobs, RouterInterface $router)
    {
        parent::__construct();
        $this->availableJobs = $availableJobs;
        $this->enabledJobs = $enabledJobs;
        $this->router = $router;
    }

    protected function configure()
    {
        $this
            ->setName('monitoring:list:available')
            ->setDescription('List the available monitoring jobs');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $failed = 0;

        // Print table with for each available job:
        // - the name
        // - if the job is currently active
        // - the job endpoint (only if actieve)
        // - the current status of the job  (only if active)
        $table = new Table($output);
        $table->setHeaders(['Monitoring task', 'Active', 'Endpoint', 'Status']);
        foreach ($this->availableJobs->getJobs() as $key => $job) {
            try {
                $entry = $this->enabledJobs->getJobByClassName($key);
                $active = $entry->isEnabled();
                $endpoint = $this->router->generate('monitoring_jobstatus', ['job' => $entry->getId()]);
                if ($entry->isEnabled()) {
                    $status = $this->printStatus($job->run());
                    // Count the failed jobs
                    if ($job->run() instanceof Failure) {
                        ++$failed;
                    }
                }
            } catch (\InvalidArgumentException $e) {
                // Not in list of active jobs
                $endpoint = '';
                $active = false;
                $status = '';
            }

            $table->addRow([
                $key,
                $active ? 'yes' : 'no',
                $endpoint,
                $status
            ]);
        }
        $table->render();

        if ($failed === 0) {
            $this->io->success('No monitoring tasks failed');
            return 0;
        }

        $this->io->error($failed . ' monitoring jobs returned a failed status');
        return 1; // console error code
    }

    /**
     * Show as OK or ERR
     *
     * @param StatusInterface $status
     * @return string
     */
    private function printStatus(StatusInterface $status): string
    {
        if ($status instanceof Success) {
            return '<info>OK</info>';
        }
        return '<error>ERR</error>';
    }
}
