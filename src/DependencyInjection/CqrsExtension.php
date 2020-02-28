<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Tuzex\Cqrs\CommandHandler;
use Tuzex\Cqrs\QueryHandler;

final class CqrsExtension extends Extension
{
    private FileLocator $fileLocator;

    public function __construct()
    {
        $this->fileLocator = new FileLocator(__DIR__.'/../Resources/config');
    }

    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $this->registerTags($containerBuilder);
        $this->registerServices($containerBuilder);
    }

    private function registerTags(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->registerForAutoconfiguration(CommandHandler::class)
            ->addTag('tuzex.cqrs.command_handler')
            ->addTag('messenger.message_handler', ['bus' => 'tuzex.cqrs.command_bus']);

        $containerBuilder->registerForAutoconfiguration(QueryHandler::class)
            ->addTag('tuzex.cqrs.query_handler')
            ->addTag('messenger.message_handler', ['bus' => 'tuzex.cqrs.query_bus']);
    }

    private function registerServices(ContainerBuilder $containerBuilder): void
    {
        $loader = new XmlFileLoader($containerBuilder, $this->fileLocator);
        $loader->load('services.xml');
    }
}
