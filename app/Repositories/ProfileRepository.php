<?php
namespace App\Repositories;
use App\Repositories\Contracts\ProfileRepositoryContract;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
class ProfileRepository implements ProfileRepositoryContract
{
    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();
        return $user->refresh();
    }
    public function deletePhoto(User $user)
    {
        if (!$user->photo) {
            return;
            }
            Storage::disk('public')->delete($user->photo->path);
            $user->photo->delete();
    }
    public function storePhoto(User $user,UploadedFile $image)
    {
        $path = $image->store('employees', 'public');
        $user->photo()->create([
            'path' => $path,
            'disk' => 'public',
        ]);
    }
    public function replacePhoto(User $user, UploadedFile $image): void
    {
        $this->deletePhoto($user);
        $this->storePhoto($user, $image);
    }
    public function updatePassword(User $user, string $password): void
    {
        $user->update([
            'password' => Hash::make($password),
        ]);
    }
}