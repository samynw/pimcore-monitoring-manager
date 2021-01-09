<?php

namespace MonitoringManagerBundle\Service\Status;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class Teapot
 *
 * You're probably not gonna use this one.
 *
 * @package MonitoringManagerBundle\Service\Status
 */
class Teapot extends Failure
{
    /**
     * Someone was trying to make coffee with a teapot
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return Response::HTTP_I_AM_A_TEAPOT;
    }
}
