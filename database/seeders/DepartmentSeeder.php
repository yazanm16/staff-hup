<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['IT', 'Front-End', 'Back-End', 'QA'];
        foreach ($departments as $department) {
            Department::create([
                'name'=> $department
            ]);
        }
    }
}
