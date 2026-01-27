<?php

class UpdateChangelog
{
    public const NEW_LOG_MESSAGE = 'Automated code refactoring for HumHub %s using Rector';

    public static function run(): void
    {
        $moduleJsonPath = getcwd() . '/module.json';
        if (!file_exists($moduleJsonPath)) {
            return;
        }

        $moduleJson = json_decode(file_get_contents($moduleJsonPath), true);
        $moduleVersion = $moduleJson['version'] ?? null;
        $coreVersion = $moduleJson['humhub']['minVersion'] ?? null;

        if (!$moduleVersion || !$coreVersion) {
            return;
        }

        $changelogPath = getcwd() . '/docs/CHANGELOG.md';

        if (!file_exists($changelogPath)) {
            return;
        }

        $changelog = file_get_contents($changelogPath);

        if (!preg_match('/^(\d+\.[^\s]+)/m', $changelog, $m, PREG_OFFSET_CAPTURE)) {
            // The first version block is not found in the changelog file
            return;
        }

        $newLogMessage = '- Enh: ' . sprintf(self::NEW_LOG_MESSAGE, $coreVersion) . PHP_EOL;

        if ($m[0][0] !== $moduleVersion) {
            // Add to new module version
            $insert = $moduleVersion . ' (Unreleased)' . PHP_EOL
                . str_repeat('-', strlen($moduleVersion) + 13) . PHP_EOL
                . $newLogMessage . PHP_EOL;

            $changelog = substr_replace($changelog, $insert, $m[0][1], 0);
        } else {
            // Append to existing version
            $pattern = '/^' . preg_quote($moduleVersion, '/') . '[\s\S]*?(?=\r?\n\d+\.[^\s]+|\z)/m';

            if (preg_match($pattern, $changelog, $blockMatch, PREG_OFFSET_CAPTURE)
                && !str_contains($blockMatch[0][0], $newLogMessage)) {
                $changelog = substr_replace($changelog, $newLogMessage, $blockMatch[0][1] + strlen($blockMatch[0][0]), 0);
            } else {
                // The log message was already added to the version
                return;
            }
        }

        file_put_contents($changelogPath, $changelog);
    }
}

UpdateChangelog::run();
