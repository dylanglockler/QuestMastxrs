<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'host', 'guard_name' => 'web']);

        $hosts = [
            ['email' => 'cliff.flamer@gmail.com', 'name' => 'Cliff'],
            ['email' => 'alex.flamer.iv@gmail.com', 'name' => 'Alex'],
            ['email' => 'evalineflamer@gmail.com', 'name' => 'Evaline'],
        ];

        foreach ($hosts as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('questmastxrs'),
                ]
            );
            $user->assignRole($role);
            $this->command->info("Host user ready: {$user->email}");
        }
    }
}
