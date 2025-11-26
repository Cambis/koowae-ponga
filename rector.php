<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;

return RectorConfig::configure()
    ->withImportNames()
    ->withPaths([
        __DIR__ . '/_config.php',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        privatization: true,
        earlyReturn: true,
        phpunitCodeQuality: true
    )
    ->withSets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_54,
        SilverstripeSetList::CODE_QUALITY,
    ])
    ->withSkip([
        ClosureToArrowFunctionRector::class,
        // This may cause a downgrade to fail
        AddTypeToConstRector::class,
    ]);
