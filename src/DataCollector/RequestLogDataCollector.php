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

namespace Odesk\Bundle\PhystrixBundle\DataCollector;

use Odesk\Phystrix\AbstractCommand;
use Odesk\Phystrix\RequestLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Throwable;

/**
 * Collects data from Phystrix RequestLog. Makes it compatible to use with Symfony profiler and WebProfiler.
 */
class RequestLogDataCollector extends DataCollector
{
    private RequestLog $requestLog;

    public function __construct(RequestLog $requestLog)
    {
        $this->requestLog = $requestLog;
    }

    /**
     * phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
     */
    public function collect(Request $request, Response $response, ?Throwable $exception = null): void
    {
        $this->data = ['commands' => []];

        /** @var AbstractCommand $command */
        foreach ($this->requestLog->getExecutedCommands() as $command) {
            $time = $command->getExecutionTimeInMilliseconds();
            if (!$time) {
                $time = 0;
            }
            $this->data['commands'][] = [
                'class' => get_class($command),
                'duration' => $time,
                'events' => $command->getExecutionEvents(),
            ];
        }
    }

    /**
     * @return array<string, string|int|array<string>>
     */
    public function getCommands(): array
    {
        /** @var array<string, string|int|array<string>> $data */
        $data = $this->data['commands'];

        return $data;
    }

    public function getName(): string
    {
        return 'phystrix';
    }

    /**
     * Resets this data collector to its initial state.
     */
    public function reset(): void
    {
        $this->data = [];
    }
}
