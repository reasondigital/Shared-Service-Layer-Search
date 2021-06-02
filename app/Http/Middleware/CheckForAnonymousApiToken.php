<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Checks the request's token to see if it's an anonymous one, and then ensures
 * conditions are right for anonymous tokens to function.
 *
 * @package App\Http\Middleware
 * @since 1.0.0
 */
class CheckForAnonymousApiToken
{
    /**
     * @var User
     * @since 1.0.0
     */
    private User $user;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     * @throws Exception
     * @since 1.0.0
     */
    public function handle(Request $request, Closure $next)
    {
        $requestToken = $request->bearerToken();

        if (empty($requestToken)) {
            return $next($request);
        }

        $apiTokens = config('api-tokens', []);
        $tokenInst = PersonalAccessToken::findToken($requestToken);
        $tokenIsListed = array_key_exists($requestToken, $apiTokens);
        $tokenIsStored = !is_null($tokenInst);

        if ($tokenIsListed) {
            if ($tokenIsStored) {
                return $next($request);
            } else {
                $this->getAnonymousUser()->tokens()->create([
                    'name' => $apiTokens[$requestToken]['name'],
                    'token' => hash('sha256', $requestToken),
                    'abilities' => $apiTokens[$requestToken]['abilities'],
                ]);
            }
        } else {
            // If not in the config file, delete token from storage
            if ($tokenIsStored) {
                $expiredToken = $this->getAnonymousUser()
                    ->tokens()
                    ->where('id', '=', $tokenInst->id)
                    ->first()
                ;

                if (!is_null($expiredToken)) {
                    $expiredToken->delete();
                }
            }
        }

        return $next($request);
    }

    /**
     * Get the defined anonymous user. The user will be created if it doesn't
     * exist.
     *
     * @return User
     * @since 1.0.0
     */
    private function getAnonymousUser(): User
    {
        if (isset($this->user)) {
            return $this->user;
        }

        $email = 'anonymous@api.user';
        $this->user = User::firstOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => $email,
                'password' => Hash::make(Str::random()),
            ]
        );

        return $this->user;
    }
}
