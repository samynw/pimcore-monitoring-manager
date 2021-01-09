<?php

namespace MonitoringManagerBundle\Command;

use MonitoringManagerBundle\Config\MonitoringConfig;
use MonitoringManagerBundle\Service\Listing\EnabledJobs;
use MonitoringManagerBundle\ValueObject\Config\Entry;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class DisableJobCommand extends AbstractCommand
{
    /** @var EnabledJobs */
    private $enabledJobs;
    /** @var MonitoringConfig */
    private $config;

    /**
     * EnableJobCommand constructor.
     * @param MonitoringConfig $config
     * @param EnabledJobs $enabledJobs
     */
    public function __construct(MonitoringConfig $config, EnabledJobs $enabledJobs)
    {
        parent::__construct();
        $this->enabledJobs = $enabledJobs;
        $this->config = $config;
    }

    protected function configure()
    {
        $this
            ->setName('monitoring:job:disable')
            ->setDescription('Disable a monitoring job')
            ->addArgument('job', InputArgument::OPTIONAL, 'The key of the job to disable');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = $this->getKey();

        $this->config->removeJob($key);
        $this->config->save();

        $this->io->success(sprintf('Job "%s" has been disabled', $key));
        return 0;
    }

    /**
     * Prompt user for job to disable
     *
     * @return string
     */
    private function getKey(): string
    {
        $jobs = $this->enabledJobs->getJobs();

        // Use the input argument if provided
        $job = $this->input->getArgument('job');
        if (!empty($job)) {
            if ($jobs->containsKey($job)) {
                return $job;
            }

            $this->io->error(sprintf('Invalid job "%s".', $job));
        }

        // Let the user choose a job
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Select the monitoring job you want to disable: ',
            $jobs->getKeys()
        );

        return $helper->ask($this->input, $this->output, $question);
    }
}
