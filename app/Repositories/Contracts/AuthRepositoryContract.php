<?php
namespace App\Repositories\Contracts;
use App\Models\User;

interface AuthRepositoryContract
{
    public function findByEmail(string $email);
    public function deleteUserToken($user);
    public function createToken($user,string $name,\DateTimeInterface $expiresAt);
}
