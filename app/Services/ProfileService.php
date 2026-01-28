<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\ProfileRepositoryContract;
use Illuminate\Http\UploadedFile;
class ProfileService
{
    public function __construct(protected ProfileRepositoryContract $profileRepository)
    {
        
    }
    public function updateProfile(User $user,array $data,?UploadedFile $image=null):User{
        if($image){
            $this->profileRepository->replacePhoto($user,$image);
        }
        if (isset($data['email']) && $data['email'] !== $user->email) {
            $data['email_verified_at'] = null;
        }
        return $this->profileRepository->update($user, $data);
    }
    public function changePassword(User $user,string $newPassword):void{
        $this->profileRepository->updatePassword($user,$newPassword);
    }
}