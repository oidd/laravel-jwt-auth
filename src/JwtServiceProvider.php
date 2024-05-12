<?php

namespace LaravelJwtAuth;

use Illuminate\Support\ServiceProvider;

class JwtServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $authManager = app()->make('auth');

        $authManager->extend('jwt', function ($app, $name, $config) {
            $provider = $app->make('auth')->createUserProvider($config['provider'] ?? null);
            $request = $app->make('request');

            $guard = new JwtGuard(
                $provider,
                $request,
                $app['config']['jwt.input_key'],
                $app['config']['jwt.storage_key'],
                $app['config']['jwt.token_key'],
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/jwt.php' => config_path('jwt.php'),
        ]);
    }
}
