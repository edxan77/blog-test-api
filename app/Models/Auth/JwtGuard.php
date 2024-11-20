<?php
namespace App\Models\Auth;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Log;

class JwtGuard implements Guard
{
    use GuardHelpers;

    protected $jwt;

    public function __construct(UserProvider $userProvider)
    {
        $this->provider = $userProvider;
        $this->jwt = new Jwt();
    }

    public function attempt(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);
        if ($this->hasValidCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        }
        return false;
    }

    public function validate(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        return $this->hasValidCredentials($user, $credentials);
    }

    public function user()
    {
        if (empty($this->user)) {
            $jwt = request()->bearerToken();
            if (empty($jwt)) {
                $this->user = null;
            } else {
                $invalidated = JwtBlacklist::where('token', $jwt)->first();
                if (!empty($invalidated)) {
                    throw new AuthenticationException();
                }
                $payload = $this->jwt->toUser($jwt);
                $this->user = $this->provider->retrieveById($payload->id);
            }
        }
        return $this->user;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param mixed $user
     * @param array $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return !is_null($user) &&
            $this->provider->validateCredentials($user, $credentials);
    }

    public function login(AuthenticatableContract $user)
    {
        $this->setUser($user);
    }

    public function logout()
    {
        $jwt = request()->bearerToken();
        if (!empty($jwt)) {
            JwtBlacklist::updateOrCreate(
                ['token' => $jwt],
                ['updated_at' => date('Y-m-d H:i:s')]
            );
        }
    }
}

