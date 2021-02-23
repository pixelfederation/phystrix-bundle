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
namespace Odesk\Bundle\PhystrixBundle\Tests\DependencyInjection;

use Odesk\Bundle\PhystrixBundle\DependencyInjection\OdeskPhystrixExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OdeskPhystrixExtensionTest extends TestCase
{
    private OdeskPhystrixExtension $extension;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new OdeskPhystrixExtension();
    }

    public function publicServicesNamesProvider(): array
    {
        return [
            ['phystrix.command_factory'],
            ['phystrix.service_locator'],
        ];
    }

    /**
     * @dataProvider publicServicesNamesProvider
     */
    public function testServiceIsPublic(string $serviceName): void
    {
        $container = new ContainerBuilder();
        $this->extension->load([['default' => []]], $container);

        self::assertTrue($container->hasDefinition($serviceName), "Service $serviceName must be defined");
        $definition = $container->getDefinition($serviceName);
        self::assertTrue($definition->isPublic(), "Service $serviceName must be public");
    }

    public function testDefaultConfig(): void
    {
        $container = new ContainerBuilder();
        $this->extension->load([['default' => []]], $container);

        $configArrayAll = $container->getParameter('phystrix.configuration.data');

        self::assertArrayHasKey('default', $configArrayAll);

        $defaultConfigArray = $configArrayAll['default'];

        // fallback
        self::assertArrayHasKey('fallback', $defaultConfigArray);
        self::assertEquals(false, $defaultConfigArray['fallback']['enabled']);

        // requestCache
        self::assertArrayHasKey('requestCache', $defaultConfigArray);
        self::assertEquals(true, $defaultConfigArray['requestCache']['enabled']);

        // requestLog
        self::assertArrayHasKey('requestLog', $defaultConfigArray);
        self::assertEquals(false, $defaultConfigArray['fallback']['enabled']);

        // circuitBreaker
        self::assertArrayHasKey('circuitBreaker', $defaultConfigArray);
        $circuitBreakerConfigArray = $defaultConfigArray['circuitBreaker'];

        self::assertArrayHasKey('errorThresholdPercentage', $circuitBreakerConfigArray);
        self::assertEquals(50, $circuitBreakerConfigArray['errorThresholdPercentage']);

        self::assertArrayHasKey('requestVolumeThreshold', $circuitBreakerConfigArray);
        self::assertEquals(20, $circuitBreakerConfigArray['requestVolumeThreshold']);

        self::assertArrayHasKey('sleepWindowInMilliseconds', $circuitBreakerConfigArray);
        self::assertEquals(5000, $circuitBreakerConfigArray['sleepWindowInMilliseconds']);

        self::assertArrayHasKey('forceOpen', $circuitBreakerConfigArray);
        self::assertFalse($circuitBreakerConfigArray['forceOpen']);

        self::assertArrayHasKey('forceClosed', $circuitBreakerConfigArray);
        self::assertFalse($circuitBreakerConfigArray['forceClosed']);

        // metrics
        self::assertArrayHasKey('metrics', $defaultConfigArray);
        $metricsConfigArray = $defaultConfigArray['metrics'];

        self::assertArrayHasKey('healthSnapshotIntervalInMilliseconds', $metricsConfigArray);
        self::assertEquals(1000, $metricsConfigArray['healthSnapshotIntervalInMilliseconds']);

        self::assertArrayHasKey('rollingStatisticalWindowInMilliseconds', $metricsConfigArray);
        self::assertEquals(1000, $metricsConfigArray['rollingStatisticalWindowInMilliseconds']);

        self::assertArrayHasKey('rollingStatisticalWindowBuckets', $metricsConfigArray);
        self::assertEquals(10, $metricsConfigArray['rollingStatisticalWindowBuckets']);
    }

    public function testChangedConfig()
    {
        $changedConfig = [
            'fallback' => true,
            'requestCache' => false,
            'requestLog' => true,
            'circuitBreaker' => [
                'errorThresholdPercentage' => 101,
                'forceOpen' => true,
                'forceClosed' => true,
                'requestVolumeThreshold' => 102,
                'sleepWindowInMilliseconds' => 103,
            ],
            'metrics' => [
                'healthSnapshotIntervalInMilliseconds' => 104,
                'rollingStatisticalWindowInMilliseconds' => 105,
                'rollingStatisticalWindowBuckets' => 106,
            ],
        ];

        $container = new ContainerBuilder();
        $this->extension->load([['default' => $changedConfig]], $container);

        $configArrayAll = $container->getParameter('phystrix.configuration.data');

        self::assertArrayHasKey('default', $configArrayAll);

        $defaultConfigArray = $configArrayAll['default'];

        // fallback
        self::assertArrayHasKey('fallback', $defaultConfigArray);
        self::assertEquals(true, $defaultConfigArray['fallback']['enabled']);

        // requestCache
        self::assertArrayHasKey('requestCache', $defaultConfigArray);
        self::assertEquals(false, $defaultConfigArray['requestCache']['enabled']);

        // requestLog
        self::assertArrayHasKey('requestLog', $defaultConfigArray);
        self::assertEquals(true, $defaultConfigArray['fallback']['enabled']);

        // circuitBreaker
        self::assertArrayHasKey('circuitBreaker', $defaultConfigArray);
        $circuitBreakerConfigArray = $defaultConfigArray['circuitBreaker'];

        self::assertArrayHasKey('errorThresholdPercentage', $circuitBreakerConfigArray);
        self::assertEquals(101, $circuitBreakerConfigArray['errorThresholdPercentage']);

        self::assertArrayHasKey('requestVolumeThreshold', $circuitBreakerConfigArray);
        self::assertEquals(102, $circuitBreakerConfigArray['requestVolumeThreshold']);

        self::assertArrayHasKey('sleepWindowInMilliseconds', $circuitBreakerConfigArray);
        self::assertEquals(103, $circuitBreakerConfigArray['sleepWindowInMilliseconds']);

        self::assertArrayHasKey('forceOpen', $circuitBreakerConfigArray);
        self::assertTrue($circuitBreakerConfigArray['forceOpen']);

        self::assertArrayHasKey('forceClosed', $circuitBreakerConfigArray);
        self::assertTrue($circuitBreakerConfigArray['forceClosed']);

        // metrics
        self::assertArrayHasKey('metrics', $defaultConfigArray);
        $metricsConfigArray = $defaultConfigArray['metrics'];

        self::assertArrayHasKey('healthSnapshotIntervalInMilliseconds', $metricsConfigArray);
        self::assertEquals(104, $metricsConfigArray['healthSnapshotIntervalInMilliseconds']);

        self::assertArrayHasKey('rollingStatisticalWindowInMilliseconds', $metricsConfigArray);
        self::assertEquals(105, $metricsConfigArray['rollingStatisticalWindowInMilliseconds']);

        self::assertArrayHasKey('rollingStatisticalWindowBuckets', $metricsConfigArray);
        self::assertEquals(106, $metricsConfigArray['rollingStatisticalWindowBuckets']);
    }

    public function testConfigMustHaveDefault(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $container = new ContainerBuilder();
        $this->extension->load([[]], $container);
    }
}
