<?php
declare(strict_types=1);

/**
 * @author    mskorupa
 * @copyright PIXEL FEDERATION
 * @license:  Internal use only
 */

namespace PixelFederation\Bundle\LoginServerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 */
final class LoggerPass  implements CompilerPassInterface
{
    /**
     * @inheritdoc
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('logger.phystrix')) {
            $definition = $container->getDefinition('phystrix.service_locator');
            $definition->addMethodCall('set', ['logger', new Reference('logger.phystrix')]);
        }
    }
}
