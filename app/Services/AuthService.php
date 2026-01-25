<?php
namespace App\Services;
use App\Repositories\Contracts\AuthRepositoryContract;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class AuthService
{
    public function __construct(protected AuthRepositoryContract $authRepository)
    {
    }
    public function login(array $credentials)
    {
        if(!Auth::attempt($credentials)){
            throw new \Exception('Invalid credentials');
        }
        $user=Auth::user();
        $this->authRepository->deleteUserToken($user);
        $expiresAt = now()->addHours(12);
        $token = $this->authRepository->createToken(
            $user,
            'api_token',
            $expiresAt
        );
        return [
            'user'=>$user,
            'token'=>$token,
            'expires_at'=>$expiresAt
        ];
    }
    public function logout($user)
    {
        $this->authRepository->deleteUserToken($user);
    }
}