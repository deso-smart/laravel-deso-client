<?php

namespace DesoSmart\DesoClient;

use Illuminate\Support\ServiceProvider;

final class DesoClientServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->getPackageConfigPath() => $this->app->configPath('deso_client.php'),
            ], 'config');
        }
    }

    private function getPackageConfigPath(): string
    {
        return dirname(__DIR__) . '/config/deso_client.php';
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getPackageConfigPath(), 'deso_client');

        $this->app->singleton(DesoClient::class, function ($app) {
            return new DesoClient($app->config->get('deso_client.node_uri'));
        });
    }
}
