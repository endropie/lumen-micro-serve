<?php
namespace Endropie\LumenMicroServe\Auth\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class TokenProvider implements UserProvider
{
	private $user;

	public function __construct ($user)
    {
        $this->user = $user;
	}

	public function retrieveById ($identifier)
    {
		abort(501, 'Retrieve By ID in provider [AUTH-TOKEN] has not supported');
	}

	public function retrieveByToken ($identifier, $token)
    {
        $this->user->make($identifier->jti, (array) $identifier->data);

		return $identifier->data ? $this->user : null;
	}

	public function updateRememberToken (Authenticatable $user, $token)
    {
        abort(501, 'Update Remember Token in provider [AUTH-TOKEN] has not supported');
	}

	public function retrieveByCredentials (array $credentials)
    {
		abort(501, 'Retrieve By Credential in provider [AUTH-TOKEN] has not supported');
	}

	public function validateCredentials (Authenticatable $user, array $credentials)
    {
		abort(501, 'Validate By Credential in provider [AUTH-TOKEN] has not supported');
	}
}
