# DeSo Client for Laravel

[![License](https://poser.pugx.org/deso-smart/laravel-deso-client/license)](https://packagist.org/packages/deso-smart/laravel-deso-client)
[![Latest Stable Version](https://poser.pugx.org/deso-smart/laravel-deso-client/v/stable)](https://packagist.org/packages/deso-smart/laravel-deso-client)
[![Total Downloads](https://poser.pugx.org/deso-smart/laravel-deso-client/downloads)](https://packagist.org/packages/deso-smart/laravel-deso-client)

## Installation

Require this package with composer.

```shell
composer require deso-smart/laravel-deso-client
```

Laravel >=5.5 uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

Copy the package config to your local config with the publish command:

```shell
php artisan vendor:publish --provider="DesoSmart\DesoClient\DesoClientServiceProvider" --tag=config
```

## Usage example

```php
use DesoSmart\DesoClient\DesoClient;
// ...
public function handler(DesoClient $client)
{
    $payload = $client->getExchangeRate();

    dd($payload);
}
// ...
```
