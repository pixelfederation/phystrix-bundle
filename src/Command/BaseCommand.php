<?php

/**
 * This file is a part of the Phystrix Bundle.
 *
 * Copyright 2013-2015 oDesk Corporation. All Rights Reserved.
 *
 * This file is licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

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
