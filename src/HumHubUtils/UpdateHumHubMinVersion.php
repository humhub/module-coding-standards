<?php

namespace HumHubUtils;

class UpdateHumHubMinVersion
{
    public static function increaseVersion($minHumHubVersion)
    {
        $moduleJsonPath = getcwd() . '/module.json';
        if (file_exists($moduleJsonPath)) {
            $moduleJson = json_decode(file_get_contents($moduleJsonPath), true);
            if (self::mustBeIncreased($moduleJson['humhub']['minVersion'], $minHumHubVersion)) {
                $moduleJson['humhub']['minVersion'] = $minHumHubVersion;

                // To be sure the max version is not less than the new min version
                if (isset($moduleJson['humhub']['maxVersion'])
                    && self::mustBeIncreased($moduleJson['humhub']['maxVersion'], $minHumHubVersion)) {
                    $moduleJson['humhub']['maxVersion'] = $minHumHubVersion;
                }

                if (isset($moduleJson['version'])) {
                    $moduleJson['version'] = self::increaseVersionLevel($moduleJson['version']);
                }

                file_put_contents(
                    $moduleJsonPath,
                    json_encode($moduleJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                );
            }
        } else {
            print "********** Module JSON not found!\n\n";
        }
    }

    private static function mustBeIncreased(string $currentVersion, string $minRequiredVersion): bool
    {
        return version_compare(
            self::normalizeVersion($currentVersion),
            self::normalizeVersion($minRequiredVersion),
            '<',
        );
    }

    private static function normalizeVersion(string $version): string
    {
        // Normalize a beta version e.g. from 1.18-beta.6 to 1.18.0.6,
        // because version_compare() decides version 1.18 as stable and higher than beta.
        return preg_replace('/[^0-9.]+/', '.0', $version);
    }

    /**
     * Increase a version level
     *
     * Examples:
     *  - 1.8.4 => 2.0.0 (when $level = 1)
     *  - 1.8.4 => 1.9.0 (when $level = 2)
     *  - 1.8.4 => 1.8.5 (when $level = 3)
     *
     * @param string $version
     * @param int $level
     * @return string
     */
    private static function increaseVersionLevel(string $version, int $level = 2): string
    {
        $version = explode('.', $version);

        for ($l = 0; $l < count($version); $l++) {
            if ($l === $level - 1) {
                $version[$l] = (int) $version[$l] + 1;
            } elseif ($l >= $level) {
                $version[$l] = 0;
            }
        }

        return implode('.', $version);
    }
}