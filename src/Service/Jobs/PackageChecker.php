<?php

namespace Samynw\MonitoringManagerBundle\Service\Jobs;

use Samynw\MonitoringManagerBundle\Service\Status\Failure;
use Samynw\MonitoringManagerBundle\Service\Status\StatusInterface;
use Samynw\MonitoringManagerBundle\Service\Status\Success;
use SensioLabs\Security\SecurityChecker;

class PackageChecker implements JobInterface
{
    /** @var string */
    private $projectRoot;

    /**
     * PackageChecker constructor.
     *
     * @param string $projectRoot
     */
    public function __construct(string $projectRoot)
    {
        $this->projectRoot = $projectRoot;
    }

    public function getLabel(): string
    {
        return 'Composer security checker';
    }

    public function getDescription(): string
    {
        return 'Check for known vulnerabilities in the Composer dependencies.';
    }

    /**
     * If no affected packages were found by the Symfony checker, return a Success.
     *
     * @return StatusInterface
     */
    public function run(): StatusInterface
    {
        // Run the security checker
        $result = $this->getChecker()->check($this->projectRoot);

        // No affected packages were found
        if($result->count() === 0){
            return new Success();
        }

        return new Failure();
    }

    /**
     * Initiate the security checker
     *
     * @return SecurityChecker
     */
    private function getChecker(): SecurityChecker
    {
        return new SecurityChecker();
    }
}
