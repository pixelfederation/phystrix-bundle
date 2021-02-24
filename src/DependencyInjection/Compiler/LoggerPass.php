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

namespace Odesk\Bundle\PhystrixBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

final class LoggerPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     * @throws InvalidArgumentException
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('logger.phystrix') && !$container->hasAlias('logger.phystrix')) {
            return;
        }

        $definition = $container->getDefinition('phystrix.service_locator');
        $definition->addMethodCall('set', ['logger', new Reference('logger.phystrix')]);
    }
}
