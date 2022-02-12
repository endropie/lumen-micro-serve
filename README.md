# lumen-micro-serve

Simple microservice for Lumen Framework.
- Authentication on Auth-Service.
- Authorization on other services.
- Model Filterable [soon].
- Model JSON resource [soon].

## Installation

Standard [Composer](https://getcomposer.org/download) package installation:

```sh
composer require Endropie/LumenMicroServe
```

## Usage

### Authentication on Auth-Service

1. Publish the config file. This will create a `config/jwt.php` file for basic configuration options.

```sh
php artisan vendor:publish --provider="Endropie\LumenMicroserve\AuthServiceProvider" --tag="config"
```

2. Add a new auth guard to your auth config file using a `jwt` driver.

```php
// config/auth.php

'guards' => [
	'web' => [
		'driver' => 'session',
		'provider' => 'users',
	],

	'api' => [
		'driver' => 'jwt',
		'provider' => 'users',
	],
],


'providers' => [
    'users' => [
        'driver' => 'eloquent',
        // Model eloquent for auth user provider 
        'model' => App\Models\User::class,
    ],
],

```

3. Protect your API routes using this new guard.

```php
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/user', function() {
        return auth()->user()->toArray();
    });
});
```

4. Use provided `AuthorizableToken` trait from this package on your Auth model (eg. User).

```php
namespace App\Models;

use Endropie\LumenMicroServe\Auth\Concerns\AuthorizableToken;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable, AuthorizableToken;
}
```

You now have access to `token()` method on your User model, eg:

```php
$user = User::findOrFail(1);
$user->token();
```

You should probably return this token via Login Controller or User Resource.

### Authorization on other services.

1. Publish the config file. This will create a `config/jwt.php` file for basic configuration options.

```sh
php artisan vendor:publish --provider="Endropie\LumenMicroserve\AuthTokenServiceProvider" --tag="config"
```

2. Protect your API routes using this new guard.

```php
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/user', function() {
        return auth()->user()->toArray();
    });
});
```

You now have access to `auth()` helper function, eg:

```php
	auth()->user();
```

You should probably return this token via Login Controller or User Resource.

## Configuration

This package provides simple configuration via `config/jwt.php` file after you publish the config. Let's go over each configuration option.

- `secret-key` - Secret key to use when encoding / decoding tokens. It should be a random string. Remember, if you change this key all active JWT tokens will be invalidated.
- `hash-algo` - Hashing algorithm. List of supported ones are in the config file. You probably don't need to change this.
- `expiration` - Default token expiration time in minutes. You can set it to `null` and the tokens will never expire.
- `claims` - Default claims that will be applied to all tokens (besides the required ones needed for decoding and validation).

This was global configuration for all tokens. Besides that, library provides a local per-model configuration via `HasJwt` trait helper methods.

- `getJwtId()` - It should return the model unique key used to retrieve that model from database. It defaults to model primary key.
- `getJwtValidFromTime()` - It should return `null` (default) or a Carbon instance. You can use that if you want to create tokens which are not active right away.
- `getJwtValidUntilTime()` - It should return `null` or a Carbon instance. This sets the JWT expiration time which, by default, uses the `expiration` option from the config file.
- `getJwtCustomClaims()` - Should return a key/value array of extra custom claims that you want to be a part of your token. By default it's an empty array.

You can also use configuration directly on the `token()` method which then overrides all other configurations, eg:

```php
$user->token([
	'id' => $user->email,
	'valid_from' => now()->addHour(),
	'valid_until' => now()->addDay(),
	'claims' => [
		'extra1' => 'foo',
		'extra2' => 'bar'
	]
]);
```

You don't need to override all configuration options, just the ones that you wish to change.

## Request

Token is extracted from the request in one of three ways:

1. From `Authorization: Bearer {token}` header (most common).
2. From URL query param `token`.
3. From request payload using `token` field name.
