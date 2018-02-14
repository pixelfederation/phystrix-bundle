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
namespace Odesk\Bundle\PhystrixBundle\DataCollector;

use Odesk\Phystrix\AbstractCommand;
use Odesk\Phystrix\RequestLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Collects data from Phystrix RequestLog. Makes it compatible to use with Symfony profiler and WebProfiler.
 */
class RequestLogDataCollector extends DataCollector
{
    private $requestLog;

    public function __construct(RequestLog $requestLog)
    {
        $this->requestLog = $requestLog;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array('commands' => array());

        /** @var AbstractCommand $command */
        foreach ($this->requestLog->getExecutedCommands() as $command) {
            $time = $command->getExecutionTimeInMilliseconds();
            if (!$time) {
                $time = 0;
            }
            $this->data['commands'][] = array(
                'class' => get_class($command),
                'duration' => $time,
                'events' => $command->getExecutionEvents(),
            );
        }
    }

    public function getCommands()
    {
        return $this->data['commands'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'phystrix';
    }

    /**
     * Resets this data collector to its initial state.
     */
    public function reset()
    {
        $this->data = [];
    }
}
