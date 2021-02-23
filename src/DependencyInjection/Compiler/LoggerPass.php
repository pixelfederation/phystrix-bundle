<?php

declare(strict_types=1);

/*
 * @author mskorupa
 * @copyright PIXEL FEDERATION
 * @license: Internal use only
 */

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
