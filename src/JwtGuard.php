<?php

namespace LaravelJwtAuth;

use Firebase\JWT\Key;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use LogicException;
use stdClass;
use UnexpectedValueException;

class JwtGuard extends TokenGuard
{
    protected string $secretKey;

    /**
     * @param UserProvider $provider
     * @param Request $request
     * @param string $inputKey      A field in request input containing jwt
     * @param string $storageKey    A field in user model containing credential
     * @param string $tokenKey      A field in jwt payload containing user credential
     */
    public function __construct(
        UserProvider     $provider,
        Request          $request,
        string           $inputKey,
        string           $storageKey,
        protected string $tokenKey,
    )
    {
        parent::__construct($provider, $request, $inputKey, $storageKey);

        if (empty(env('JWT_SECRET'))) {
            throw new UnexpectedValueException('JWT_SECRET constant not defined in .env file.');
        }

        $this->secretKey = env('JWT_SECRET');
    }

    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (empty($token)) {
            return null;
        }

        if (($obj = $this->validateJWT($token)) === false) {
            return null;
        }

        if (!empty($obj->{$this->tokenKey})) {
            $user = $this->provider->retrieveByCredentials([
                $this->storageKey => $obj->{$this->tokenKey},
            ]);
        }

        return $user;
    }

    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->inputKey])) {
            return false;
        }

        if ($this->validateJWT($credentials[$this->inputKey]) !== false)
            return true;

        return false;
    }

    public function validateJWT(string $token) : StdClass | bool
    {
        try {
            return JWT::decode($token, new Key($this->secretKey, 'HS256'));
        } catch (LogicException|UnexpectedValueException $e) {
            return false;
        }
    }
}
