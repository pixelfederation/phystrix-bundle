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

namespace Odesk\Bundle\PhystrixBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration provides schema for the bundle configuration.
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('phystrix');
        $rootNode = $treeBuilder->getRootNode();

        /**
         * @formatter:off
         *
         * @codingStandardsIgnoreStart
         * @psalm-suppress PossiblyNullReference unable to determine correct type due limitation of symfony ArrayNodeDefinition
         * @psalm-suppress MixedMethodCall unable to determine correct type due limitation of symfony ArrayNodeDefinition
         * @psalm-suppress PossiblyUndefinedMethod unable to determine correct type due limitation of symfony ArrayNodeDefinition
         */
        $rootNode
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->arrayNode('fallback')->canBeEnabled()->end()
                    ->arrayNode('circuitBreaker')
                        ->addDefaultsIfNotSet()
                        ->canBeEnabled()
                        ->children()
                            ->integerNode('errorThresholdPercentage')->defaultValue(50)->end()
                            ->integerNode('requestVolumeThreshold')->defaultValue(20)->end()
                            ->integerNode('sleepWindowInMilliseconds')->defaultValue(5_000)->end()
                            ->booleanNode('forceOpen')->defaultValue(false)->end()
                            ->booleanNode('forceClosed')->defaultValue(false)->end()
                        ->end()
                    ->end()
                    ->arrayNode('metrics')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->integerNode('healthSnapshotIntervalInMilliseconds')->defaultValue(1_000)->end()
                            ->integerNode('rollingStatisticalWindowInMilliseconds')->defaultValue(1_000)->end()
                            ->integerNode('rollingStatisticalWindowBuckets')->defaultValue(10)->end()
                        ->end()
                    ->end()
                    ->arrayNode('requestCache')->canBeDisabled()->end()
                    ->arrayNode('requestLog')->canBeEnabled()->end()
                ->end()
            ->end()
            ->validate()
                ->ifTrue(static fn ($data) => !array_key_exists('default', $data))
                ->thenInvalid("'default' configuration should be set")
            ->end();
        /**
         * @codingStandardsIgnoreEnd
         * @formatter:on
         */

        return $treeBuilder;
    }
}
