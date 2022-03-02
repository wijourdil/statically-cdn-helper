<?php

namespace Wijourdil\Statically\Tests\Unit;

use Exception;
use Illuminate\Support\Facades\Config;
use Wijourdil\Statically\CdnHelper;
use Wijourdil\Statically\Tests\TestCase;

class CdnHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        CdnHelper::clearManifests();
        $this->deleteFakeMixManifest();

        Config::set('statically-cdn.enabled', true);
        Config::set('statically-cdn.cdn-domain', 'cdn.statically.io');
        Config::set('statically-cdn.site-domain', 'my-site.com');
    }

    /** @test */
    public function it_can_generate_a_cdn_url()
    {
        $this->assertEquals(
            '//cdn.statically.io/js/my-site.com/js/app.js',
            cdn('js/app.js')
        );

        $this->assertEquals(
            '//cdn.statically.io/css/my-site.com/css/app.css',
            cdn('css/app.css')
        );

        $this->assertEquals(
            '//cdn.statically.io/img/my-site.com/images/avatar/user.webp',
            cdn('images/avatar/user.webp')
        );
    }

    /** @test */
    public function it_can_generate_a_cdn_url_with_a_full_url_as_domain()
    {
        Config::set('statically-cdn.site-domain', 'https://my-site.com');

        $this->assertEquals(
            '//cdn.statically.io/js/my-site.com/js/app.js',
            cdn('js/app.js')
        );
    }

    /** @test */
    public function it_can_generate_a_cdn_url_with_mix_manifest()
    {
        $this->fakeMixManifest([
            '/css/build.css' => '/css/build.css?id=Nvuh2mejemZg46jwbk1iFcLmZrge1aVa',
            '/images/avatar/user.png' => '/images/avatar/user.png?id=rXlurbHq2cRXfXLneNTzxBHUhN65Rjy5',
        ]);

        $this->assertEquals(
            '//cdn.statically.io/js/my-site.com/js/app.js',
            cdn('js/app.js')
        );

        $this->assertEquals(
            '//cdn.statically.io/css/my-site.com/css/build.css?id=Nvuh2mejemZg46jwbk1iFcLmZrge1aVa',
            cdn('css/build.css')
        );

        $this->assertEquals(
            '//cdn.statically.io/img/my-site.com/images/avatar/user.png?id=rXlurbHq2cRXfXLneNTzxBHUhN65Rjy5',
            cdn('images/avatar/user.png')
        );
    }

    /** @test */
    public function it_can_generate_a_cdn_url_if_cdn_helper_is_disabled()
    {
        Config::set('statically-cdn.enabled', false);

        $this->fakeMixManifest([
            '/js/app.js' => '/js/app.js?id=h3OV5XWsgt5C2yxjumIoxF4xweWJGlQc',
            '/css/build.css' => '/css/build.css?id=Nvuh2mejemZg46jwbk1iFcLmZrge1aVa',
            '/images/avatar/user.png' => '/images/avatar/user.png?id=rXlurbHq2cRXfXLneNTzxBHUhN65Rjy5',
        ]);

        $this->assertEquals(
            asset('js/app.js'),
            cdn('js/app.js')
        );

        $this->assertEquals(
            asset('css/build.css'),
            cdn('css/build.css')
        );

        $this->assertEquals(
            asset('images/avatar/user.png'),
            cdn('images/avatar/user.png')
        );
    }

    /** @test */
    public function it_throws_an_exception_if_cdn_domain_in_config_is_invalid()
    {
        Config::set('statically-cdn.cdn-domain', ['wrong-type']);

        $this->expectException(Exception::class);

        cdn('asset.js');
    }

    /** @test */
    public function it_throws_an_exception_if_site_domain_in_config_is_invalid()
    {
        Config::set('statically-cdn.site-domain', ['wrong-type']);

        $this->expectException(Exception::class);

        cdn('asset.js');
    }

    /** @test */
    public function it_throws_an_exception_if_file_types_in_config_is_invalid()
    {
        Config::set('statically-cdn.extensions', 'not-an-array');

        $this->expectException(Exception::class);

        cdn('asset.js');
    }
}
