<?php

namespace Samynw\MonitoringManagerBundle\Service\Status;

use Symfony\Component\HttpFoundation\Response;

class Failure extends AbstractStatus
{
    public function getStatus(): string
    {
        return self::FAILURE;
    }

    /**
     * Default failure returns a 503 status
     * Intentionally not 500, to distinguish failure status from errors while performing check
     *
     * Custom status could return other codes to your own needs
     * You can extend custom failure classes from this one and override the return code
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return Response::HTTP_SERVICE_UNAVAILABLE;
    }

    /**
     * Returns 129 error code
     *
     * @see https://tldp.org/LDP/abs/html/exitcodes.html
     * @return int
     */
    public function getConsoleExitCode(): int
    {
        return 128+1;
    }
}
