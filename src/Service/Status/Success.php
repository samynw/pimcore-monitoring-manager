<?php

namespace MonitoringManagerBundle\Service\Status;

use Symfony\Component\HttpFoundation\Response;

class Success extends AbstractStatus
{
    public function getStatus(): string
    {
        return self::SUCCESS;
    }

    /**
     * 200 OK
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return Response::HTTP_OK;
    }

    /**
     * Console commands exiting with status 0 are success
     *
     * @return int
     */
    public function getConsoleExitCode(): int
    {
        return 0;
    }
}
