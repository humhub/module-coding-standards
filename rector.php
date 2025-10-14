<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

// ToDo: Add some code to increase the HumHub Core Min Version to the version required.
//       This should matches the minPHP Version specified below in the Rector Rules

return RectorConfig::configure()
    ->withPaths([
        getcwd(),
    ])
    ->withSkip([
        \Rector\Php81\Rector\Array_\FirstClassCallableRector::class,
        \Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector::class,
        getcwd() . '/vendor',
        getcwd() . '/messages',
    ])
    ->withPhpSets(php82: true)
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0)
    ->withRules([
        // Some own rules
    ])
    ->withConfiguredRule(
        RenameClassRector::class,
        [
            //'OldNamespace\\OldClass' => 'NewNamespace\\NewClass',
        ]
    );