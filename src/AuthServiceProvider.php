<?php

namespace Endropie\LumenMicroServe;

use Illuminate\Support\ServiceProvider;
use Endropie\LumenMicroServe\Auth\Guard\JWTGuard;
use Endropie\LumenMicroServe\Auth\Guard\TokenGuard;
use Endropie\LumenMicroServe\Auth\Providers\TokenProvider;
use Endropie\LumenMicroServe\Auth\TokenUser;

class AuthServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		$this->publishConfig();

		$this->extendAuthGuard();
	}

	public function register(): void
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/jwt.php', 'jwt');
        $this->mergeConfigFrom(__DIR__ . '/../config/auth.php', 'auth');
	}

	private function publishConfig(): void
	{
		$this->publishes([
			__DIR__ . '/../config/jwt.php' => app()->basePath() . '/config/jwt.php',
		], 'config');

		$this->publishes([
			__DIR__ . '/../config/auth.php' => app()->basePath() . '/config/auth.php',
		], 'config');
	}

	private function extendAuthGuard(): void
	{	
		$this->app->auth->extend('jwt', function ($app, $name, array $config) {

			if ($config['provider'] == 'token') {
				return new TokenGuard(new TokenProvider(TokenUser::class));
			}
			
			return new JWTGuard(auth()->createUserProvider($config['provider']));
		});
	}
}
