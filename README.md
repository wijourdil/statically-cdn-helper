[![Latest Version on Packagist](https://img.shields.io/packagist/v/wijourdil/statically-cdn-helper.svg?style=flat-square)](https://packagist.org/packages/wijourdil/statically-cdn-helper)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/wijourdil/statically-cdn-helper/tests?label=tests&style=flat-square)](https://github.com/wijourdil/statically-cdn-helper/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wijourdil/statically-cdn-helper.svg?style=flat-square)](https://packagist.org/packages/wijourdil/statically-cdn-helper)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/wijourdil/statically-cdn-helper)
![Packagist License](https://img.shields.io/packagist/l/wijourdil/statically-cdn-helper)
[![gitmoji.dev](https://img.shields.io/badge/gitmoji-%20😜%20😍-FFDD67.svg?style=flat)](https://gitmoji.dev)

# ⚡ [Statically.io](https://statically.io/) Laravel/Lumen  helper

## Installation

```shell
composer require wijourdil/statically-cdn-helper
```

### Laravel

Nothing to do,the package will be discovered automatically.

### Lumen

Register the package service provider in your `bootstrap/app.php` file:
```php
$app->register(\Wijourdil\Statically\StaticallyCdnHelperServiceProvider::class);
```

## Configuration

You can define the following constants in your `.env` file:
```dotenv
# What: (de)activate the cdn helper
# Allowed value: true | false
# Default: false
CDN_ENABLED=true

# What: define your website domain to use in generated cdn url
# Allowed value: any string containing a valid domain
# Default: env('APP_URL')
CDN_SITE_DOMAIN="www.my-website.com"
```

## Usage

Just use the `cdn()` helpers instead of `asset()` of `mix()`
```php
// Before
asset('img/photo.png')
// => 'https://site.com/img/photo.png'

// After, without mix-manifest.json 
cdn('img/photo.png')
// => '//cdn.statically.io/img/site.com/img/photo.png'

// After, with mix-manifest.json 
cdn('img/photo.png')
// => '//cdn.statically.io/img/site.com/img/photo.png?id=23ea1efe0290977b58d454f5164b2a32'
```
