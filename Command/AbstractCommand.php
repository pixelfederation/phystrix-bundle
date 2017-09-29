<?php
declare(strict_types=1);

/**
 * @author    mskorupa
 * @copyright PIXEL FEDERATION
 * @license:  Internal use only
 */

namespace Odesk\Bundle\PhystrixBundle\Command;

use Odesk\Phystrix\AbstractCommand as PhystrixAbstractCommand;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractCommand extends PhystrixAbstractCommand
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @return LoggerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    protected function getLogger(): LoggerInterface
    {
        if ($this->logger !== null) {
            return $this->logger;
        }

        try {
            $this->logger = $this->serviceLocator->get('logger');
        } catch (NotFoundExceptionInterface $e) {
            $this->logger = new NullLogger();
        }

        return $this->logger;
    }
}
