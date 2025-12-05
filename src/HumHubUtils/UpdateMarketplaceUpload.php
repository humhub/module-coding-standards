<?php

namespace HumHubUtils;

class UpdateMarketplaceUpload
{
    private const OLD_USES = 'humhub/actions/.github/workflows/module-marketplace-upload.yml@main';
    private const NEW_USES = 'humhub/module-coding-standards/.github/workflows/marketplace-upload.yml@main';

    public static function run(): void
    {
        $file = getcwd() . '/.github/workflows/marketplace-upload.yml';

        if (!file_exists($file)) {
            echo "********** marketplace-upload.yml is not found — skipping\n";
            return;
        }

        $content = file_get_contents($file);

        if (!str_contains($content, self::OLD_USES)) {
            print "********** Old line is not found — skipping\n";
            return;
        }

        // Update to new 'uses':
        $content = str_replace(self::OLD_USES, self::NEW_USES, $content);
        // Remove all input params:
        $content = preg_replace('/(uses:.*?\n)(?:\s+.*\n)*(\s*secrets:.*)/m', '$1$2', $content);

        file_put_contents($file, $content);

        print "********** marketplace-upload.yml updated!\n";
    }
}
