<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Tuzex\Cqrs\CommandHandler;
use Tuzex\Cqrs\QueryHandler;

final class CqrsExtension extends Extension implements PrependExtensionInterface
{
    private FileLocator $fileLocator;

    public function __construct()
    {
        $this->fileLocator = new FileLocator(__DIR__.'/../Resources/config');
    }

    public function prepend(ContainerBuilder $containerBuilder): void
    {
        $configuration = new Configuration(false);
        $configs = $this->processConfiguration($configuration, $containerBuilder->getExtensionConfig('framework'));

        $containerBuilder->prependExtensionConfig('framework', [
            'messenger' => [
                'default_bus' => $configs['messenger']['default_bus'] ?? 'tuzex.cqrs.command_bus',
                'buses' => [
                    'tuzex.cqrs.command_bus' => [],
                    'tuzex.cqrs.query_bus' => [],
                ],
            ],
        ]);

        $containerBuilder->registerForAutoconfiguration(CommandHandler::class)
            ->addTag('tuzex.cqrs.command_handler')
            ->addTag('messenger.message_handler', [
                'bus' => 'tuzex.cqrs.command_bus',
            ]);

        $containerBuilder->registerForAutoconfiguration(QueryHandler::class)
            ->addTag('tuzex.cqrs.query_handler')
            ->addTag('messenger.message_handler', [
                'bus' => 'tuzex.cqrs.query_bus',
            ]);
    }

    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $loader = new XmlFileLoader($containerBuilder, $this->fileLocator);
        $loader->load('services.xml');
    }
}
