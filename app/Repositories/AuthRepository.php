<?php
namespace App\Repositories;
use App\Repositories\Contracts\AuthRepositoryContract;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class AuthRepository implements AuthRepositoryContract
{
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function deleteUserToken($user)
    {
        return $user->tokens()->delete();
    }

    public function createToken($user, string $name, \DateTimeInterface $expiresAt)
    {
        $token = $user->createToken($name);
        $token->accessToken->update(['expires_at' => $expiresAt]);
        return $token->plainTextToken;
    }
}