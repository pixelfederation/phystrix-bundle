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
namespace Odesk\Bundle\PhystrixBundle\Tests\DataCollector;

use Odesk\Bundle\PhystrixBundle\DataCollector\RequestLogDataCollector;
use Odesk\Phystrix\AbstractCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Odesk\Phystrix\RequestLog;

class RequestLogDataCollectorTest extends TestCase
{
    public function testCollect(): void
    {
        $requestLogMock = $this->getMockBuilder(RequestLog::class)
            ->disableOriginalConstructor()
            ->getMock();
        $requestLogMock->expects(self::once())
            ->method('getExecutedCommands')
            ->willReturn([
                    $this->prepareCommandMock('Command1', 234, ['e11', 'e12']),
                    $this->prepareCommandMock('Command2', 345, ['e2']),
                    $this->prepareCommandMock('Command3', 456, ['e31', 'e32', 'e33']),
                ]);

        $collector = new RequestLogDataCollector($requestLogMock);
        $collector->collect(new Request(), new Response());

        self::assertEquals([
                ['class' => 'Command1', 'duration' => 234, 'events' => ['e11', 'e12']],
                ['class' => 'Command2', 'duration' => 345, 'events' => ['e2']],
                ['class' => 'Command3', 'duration' => 456, 'events' => ['e31', 'e32', 'e33']],
            ], $collector->getCommands());

        self::assertSame('phystrix', $collector->getName());
    }

    /**
     * @return MockObject|AbstractCommand
     */
    private function prepareCommandMock(string $name, int $executionTime, array $executionEvents)
    {
        $commandMock = $this->getMockBuilder(AbstractCommand::class)
            ->disableOriginalConstructor()
            ->setMockClassName($name)
            ->onlyMethods(['getExecutionTimeInMilliseconds', 'getExecutionEvents'])
            ->getMockForAbstractClass();
        $commandMock->expects(self::once())
            ->method('getExecutionTimeInMilliseconds')
            ->willReturn($executionTime);
        $commandMock->expects(self::once())
            ->method('getExecutionEvents')
            ->willReturn($executionEvents);

        return $commandMock;
    }
}
