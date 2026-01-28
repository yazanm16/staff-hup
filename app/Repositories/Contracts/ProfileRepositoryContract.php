<?php
namespace App\Repositories\Contracts;
use Illuminate\Http\UploadedFile;
use App\Models\User;

interface ProfileRepositoryContract
{
    public function update(User $user, array $data):User;
    public function deletePhoto(User $user);
    public function storePhoto(User $user, UploadedFile $image);
    public function replacePhoto(User $user, UploadedFile $image);
    public function updatePassword(User $user, string $newPassword):void;
    
}