<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates secure admin user for Digital Transformation Consulting business
     */
    public function run(): void
    {
        // Remove insecure default admin if exists
        User::where('email', 'admin@gmail.com')->delete();

        // Create professional admin user
        User::updateOrCreate(
            ['email' => 'admin@alisadikin.com'],
            [
                'name' => 'Ali Sadikin - Digital Transformation Consultant',
                'email' => 'admin@alisadikin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('AdminSecure2024!@#'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create backup admin access
        User::updateOrCreate(
            ['email' => 'ali@portfolio.com'],
            [
                'name' => 'Ali Sadikin - Portfolio Admin',
                'email' => 'ali@portfolio.com',
                'email_verified_at' => now(),
                'password' => Hash::make('SecurePortfolio2024!'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('✅ Professional admin users created successfully');
        $this->command->info('📧 Primary: admin@alisadikin.com / AdminSecure2024!@#');
        $this->command->info('📧 Backup: ali@portfolio.com / SecurePortfolio2024!');
        $this->command->warn('🔒 IMPORTANT: Change passwords after first login');
    }
}