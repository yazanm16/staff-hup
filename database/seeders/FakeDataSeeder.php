<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Task;
use App\Models\Attendance;
use App\Models\Comment;
use App\Models\Photo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder adds fake data WITHOUT deleting existing data.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting to seed fake data...');

        // Get existing departments or create if none exist
        $departments = Department::all();
        if ($departments->isEmpty()) {
            $this->command->warn('No departments found. Creating default departments...');
            $departmentNames = ['IT', 'Front-End', 'Back-End', 'QA', 'HR', 'Marketing', 'Sales', 'Finance'];
            foreach ($departmentNames as $name) {
                Department::firstOrCreate(['name' => $name]);
            }
            $departments = Department::all();
        }

        $this->command->info("✓ Found {$departments->count()} departments");

        // Get or create roles
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        
        $this->command->info('✓ Roles ready');

        // Create 20 fake employees
        $this->command->info('Creating 20 fake employees...');
        $users = [];
        
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => fake()->phoneNumber(),
                'position' => fake()->randomElement([
                    'Software Engineer', 'Senior Developer', 'Junior Developer',
                    'QA Engineer', 'DevOps Engineer', 'UI/UX Designer',
                    'Product Manager', 'Team Lead', 'Business Analyst'
                ]),
                'department_id' => $departments->random()->id,
            ]);

            // Assign role (80% employees, 20% managers)
            $role = (rand(1, 100) <= 80) ? $employeeRole : $managerRole;
            $user->assignRole($role);

            $users[] = $user;
        }

        $this->command->info("✓ Created 20 employees");

        // Create attendance records for the last 30 days
        $this->command->info('Creating attendance records for the last 30 days...');
        $attendanceCount = 0;
        
        foreach ($users as $user) {
            for ($day = 0; $day < 30; $day++) {
                // 85% chance of attendance per day
                if (rand(1, 100) <= 85) {
                    $date = now()->subDays($day);
                    
                    // Skip weekends
                    if ($date->isWeekend()) {
                        continue;
                    }

                    $checkIn = $date->copy()->setTime(rand(7, 9), rand(0, 59));
                    $checkOut = $checkIn->copy()->addHours(rand(7, 10))->addMinutes(rand(0, 59));
                    $workHours = $checkOut->diffInHours($checkIn, true);

                    Attendance::create([
                        'user_id' => $user->id,
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                        'work_hours' => round($workHours, 2),
                        'date' => $date->toDateString(),
                    ]);
                    
                    $attendanceCount++;
                }
            }
        }

        $this->command->info("✓ Created {$attendanceCount} attendance records");

        // Create 50 tasks
        $this->command->info('Creating 50 tasks...');
        $tasks = [];
        
        for ($i = 1; $i <= 50; $i++) {
            $task = Task::create([
                'title' => fake()->sentence(rand(3, 8)),
                'description' => fake()->paragraph(rand(2, 5)),
                'user_id' => collect($users)->random()->id,
                'status' => fake()->randomElement(['Pending', 'In-Progress', 'Completed']),
                'due_date' => fake()->dateTimeBetween('-10 days', '+30 days')->format('Y-m-d'),
            ]);
            
            $tasks[] = $task;
        }

        $this->command->info("✓ Created 50 tasks");

        // Create comments on tasks
        $this->command->info('Creating comments on tasks...');
        $commentCount = 0;
        
        foreach ($tasks as $task) {
            // Each task gets 0-5 comments
            $numComments = rand(0, 5);
            
            for ($j = 0; $j < $numComments; $j++) {
                Comment::create([
                    'task_id' => $task->id,
                    'body' => fake()->paragraph(rand(1, 3)),
                    'user_id' => collect($users)->random()->id,
                    'user_type' => User::class,
                ]);
                
                $commentCount++;
            }
        }

        $this->command->info("✓ Created {$commentCount} comments");

        $this->command->info('');
        $this->command->info('🎉 Fake data seeding completed successfully!');
        $this->command->info('');
        $this->command->info('Summary:');
        $this->command->table(
            ['Resource', 'Count'],
            [
                ['Employees', '20'],
                ['Attendance Records', $attendanceCount],
                ['Tasks', '50'],
                ['Comments', $commentCount],
            ]
        );
    }
}

