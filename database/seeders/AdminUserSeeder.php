<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\RsUser;
use SMD\Common\ReservationSystem\Enums\RoleType;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if admin user already exists
        $existingAdmin = RsUser::where('email', 'admin@ivrreservation.com')->first();
        
        if (!$existingAdmin) {
            RsUser::create([
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'email' => 'admin@ivrreservation.com',
                'password' => Hash::make('admin123456'),
                'role_id' => RoleType::ADMIN,
                'phone_number' => '1234567890',
                'pin' => '1234',
                'active' => true,
                'activated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@ivrreservation.com');
            $this->command->info('Password: admin123456');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}