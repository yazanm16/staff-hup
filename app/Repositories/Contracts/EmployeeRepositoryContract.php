<?php

namespace App\Repositories\Contracts;
use App\Models\User;
use Illuminate\Http\UploadedFile;

interface EmployeeRepositoryContract
{
    
    public function paginate(int $perPage = 5);
    public function getDepartments();
    public function getRoles();
    public function create(array $data);
    public function update(User $employee, array $data): bool;
    public function deletePhoto(User $employee);
    public function storePhoto(User $employee,UploadedFile $image);
    public function replacePhoto(User $employee, UploadedFile $image): void;
    public function syncRole(User $employee, string $role): void;
    public function delete(User $employee): bool;


}