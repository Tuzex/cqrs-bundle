<?php

declare(strict_types=1);

use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $parameters->set(Option::SETS, [
        SetList::COMMON,
        SetList::CLEAN_CODE,
        SetList::DEAD_CODE,
        SetList::PHP_71,
        SetList::PSR_12,
        SetList::SYMFONY,
    ]);

    $parameters->set(Option::SKIP, [
        PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer::class => null,
        PropertyTypeHintSniff::class . 'MissingTraversableTypeHintSpecification' => null,
        ReturnTypeHintSniff::class . 'MissingTraversableTypeHintSpecification' => null,
    ]);
};
