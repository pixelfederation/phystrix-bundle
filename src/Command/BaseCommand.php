<?php

declare(strict_types=1);

/*
 * @author mskorupa
 * @copyright PIXEL FEDERATION
 * @license: Internal use only
 */

namespace Odesk\Bundle\PhystrixBundle\Command;

use Odesk\Phystrix\AbstractCommand as PhystrixAbstractCommand;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

abstract class BaseCommand extends PhystrixAbstractCommand
{
    protected ?LoggerInterface $logger = null;

    protected function getLogger(): LoggerInterface
    {
        if ($this->logger !== null) {
            return $this->logger;
        }

        try {
            /** @var LoggerInterface $logger */
            $logger = $this->serviceLocator->get('logger');

            $this->logger = $logger;
        } catch (Throwable $e) {
            $this->logger = new NullLogger();
        }

        return $this->logger;
    }
}
