<?php

namespace Samynw\MonitoringManagerBundle\Command;

use Samynw\MonitoringManagerBundle\Config\MonitoringConfig;
use Samynw\MonitoringManagerBundle\Service\Listing\AvailableJobs;
use Samynw\MonitoringManagerBundle\Service\Listing\EnabledJobs;
use Samynw\MonitoringManagerBundle\ValueObject\Config\Entry;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Routing\RouterInterface;

class EnableJobCommand extends AbstractCommand
{
    /** @var AvailableJobs */
    private $availableJobs;
    /** @var EnabledJobs */
    private $enabledJobs;
    /** @var RouterInterface */
    private $router;
    /** @var MonitoringConfig */
    private $config;

    /**
     * EnableJobCommand constructor.
     * @param MonitoringConfig $config
     * @param AvailableJobs $availableJobs
     * @param EnabledJobs $enabledJobs
     * @param RouterInterface $router
     */
    public function __construct(MonitoringConfig $config, AvailableJobs $availableJobs, EnabledJobs $enabledJobs, RouterInterface $router)
    {
        parent::__construct();
        $this->availableJobs = $availableJobs;
        $this->enabledJobs = $enabledJobs;
        $this->router = $router;
        $this->config = $config;
    }

    protected function configure()
    {
        $this
            ->setName('monitoring:job:enable')
            ->setDescription('Enable a monitoring job');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $service = $this->getService();
        $key = $this->getKey($this->getDefaultKey($service));

        $entry = new Entry($key, $service);
        $entry->enable();

        $this->config->setJob($key, $entry);
        $this->config->save();

        $this->io->newLine();
        $this->io->success(sprintf('Enabled monitoring job "%s".', $key));

        return 0;
    }

    /**
     * Prompt user to select a valid service
     *
     * @return string
     */
    private function getService(): string
    {
        $this->io->section('Monitoring service');

        $validServices = $this->availableJobs->getJobs()->getKeys();
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Select the monitoring service you want to enable: ',
            $validServices
        );

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * Prompt user of a unique key
     *
     * @param string $default
     * @return string
     */
    private function getKey(string $default): string
    {
        $defaultUrl = $this->router->generate('monitoring_jobstatus', ['job' => $default]);

        $this->io->newLine();
        $this->io->section('Monitoring job key');
        $this->io->writeln('The key of the service will be used as');
        $this->io->writeln('a) the key of the config entry');
        $this->io->writeln('b) the URI segment in the route');
        $this->io->newLine();
        $this->io->writeln('So choose a unique key for the job');
        $this->io->writeln(sprintf('The key "%s" will translated to url "%s"', $default, $defaultUrl));
        $this->io->newLine();

        $helper = $this->getHelper('question');
        $question = new Question(
            sprintf('Please enter a key for the service (<info>default %s</info>): ', $default),
            $default
        );

        // The key has to be unique, so check if there's currently a job registered to this key
        $question->setValidator(function ($answer) {
            try {
                $job = $this->enabledJobs->getJobById($answer);
                // If no InvalidArgumentException encountered, the key is in use,
                // throw exception to let user choose a new key
                throw new \RuntimeException(
                    sprintf('Key "%s" already in use', $answer)
                );
            } catch (\InvalidArgumentException $e) {
                // key not used yet
                return $answer;
            }
        });

        // Clean user input
        $question->setNormalizer(function ($value) {
            return strtolower(trim($value));
        });

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * Based on the service, generate a default key
     *
     * @param string $service
     * @return string
     * @throws \ReflectionException
     */
    private function getDefaultKey(string $service): string
    {
        $shortName = (new \ReflectionClass($service))->getShortName();
        $shortName = ltrim(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '-$0', $shortName), '-');
        return strtolower($shortName);
    }
}
