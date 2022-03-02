<?php

namespace Wijourdil\Statically\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Wijourdil\Statically\StaticallyCdnHelperServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            StaticallyCdnHelperServiceProvider::class,
        ];
    }

    protected function fakeMixManifest(array $content): void
    {
        file_put_contents(
            base_path('public/mix-manifest.json'),
            json_encode($content)
        );
    }

    protected function deleteFakeMixManifest(): void
    {
        if (file_exists(base_path('public/mix-manifest.json'))) {
            unlink(base_path('public/mix-manifest.json'));
        }
    }
}
