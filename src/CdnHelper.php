<?php

namespace Wijourdil\Statically;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;

class CdnHelper
{
    public static array $manifests = [];

    public static function clearManifests(): void
    {
        self::$manifests = [];
    }

    public function generate(string $asset): string
    {
        if (Config::get('statically-cdn.enabled') == false) {
            return function_exists('asset')
                ? asset($asset)
                : $asset;
        }

        $siteDomain = Config::get('statically-cdn.site-domain');
        $cdnDomain = Config::get('statically-cdn.cdn-domain');
        $allowedFileExtensions = Config::get('statically-cdn.extensions');

        if (!is_array($allowedFileExtensions)) {
            throw new Exception('Configuration value statically-cdn.extensions must be an array.');
        }
        if (!is_string($siteDomain)) {
            throw new Exception('Configuration value statically-cdn.site-domain must be a string.');
        }
        if (!is_string($cdnDomain)) {
            throw new Exception('Configuration value statically-cdn.cdn-domain must be a string.');
        }

        /** @var array $parsedSiteDomain */
        $parsedSiteDomain = parse_url($siteDomain);

        if (isset($parsedSiteDomain['host'])) {
            $currentDomain = $parsedSiteDomain['host'];
        } elseif (isset($parsedSiteDomain['path'])) {
            $currentDomain = $parsedSiteDomain['path'];
        } else {
            throw new Exception("Error while parsing site domain '$siteDomain', there must be either a host or path.");
        }

        $extension = pathinfo($asset, PATHINFO_EXTENSION);

        foreach ($allowedFileExtensions as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                $asset = $this->mix($asset);
                $asset = ltrim($asset, '/');

                return "//$cdnDomain/$type/$currentDomain/$asset";
            }
        }

        return $asset;
    }

    private function mix(string $path, string $manifestDirectory = ''): string
    {
        if (!str_starts_with($path, '/')) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && !str_starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        $manifestPath = base_path("public/$manifestDirectory/mix-manifest.json");

        if (!isset(self::$manifests[$manifestPath])) {
            if (!is_file($manifestPath)) {
                return $path;
            }

            /** @var string $manifestContent */
            $manifestContent = file_get_contents($manifestPath);
            self::$manifests[$manifestPath] = json_decode($manifestContent, true);
        }

        $manifest = self::$manifests[$manifestPath];

        if (!isset($manifest[$path])) {
            return $path;
        }

        return new HtmlString($manifestDirectory . $manifest[$path]);
    }
}
