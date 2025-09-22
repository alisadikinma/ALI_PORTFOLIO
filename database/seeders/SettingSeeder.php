<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Initialize professional consulting business settings
     */
    public function run(): void
    {
        // Update professional consulting business settings using existing column structure
        DB::table('setting')->where('id_setting', 1)->update([
            'instansi_setting' => 'Ali Sadikin - Digital Transformation Consultant',
            'pimpinan_setting' => 'Ali Sadikin',
            'profile_title' => 'Digital Transformation Consultant | Manufacturing AI Expert',
            'profile_content' => '<p>Digital Transformation Consultant specializing in Manufacturing AI implementation and Smart Factory solutions. With expertise in Laravel development and enterprise modernization, I help businesses leverage technology for competitive advantage.</p>',
            'keyword_setting' => 'digital transformation consultant, manufacturing ai, smart factory, laravel developer, enterprise modernization, technology consultant, industry 4.0',
            'alamat_setting' => 'Batam, Indonesia',
            'email_setting' => 'admin@alisadikin.com',
            'no_hp_setting' => '+62-811-XXXX-XXXX',
            'linkedin_setting' => 'alisadikinma',
            'instagram_setting' => 'alisadikinma',
            'facebook_setting' => 'Ali.sadikinMa',
            'youtube_setting' => '@alisadikinma',
            'tiktok_setting' => 'alisadikinma',
            'primary_button_title' => 'Contact for Consultation',
            'primary_button_link' => 'mailto:admin@alisadikin.com',
            'secondary_button_title' => 'LinkedIn Profile',
            'secondary_button_link' => 'https://www.linkedin.com/in/alisadikinma',
            'years_experience' => '16+',
            'followers_count' => '54K+',
            'project_delivered' => '18+',
            'cost_savings' => '$250K+',
            'success_rate' => '99%',
            'about_section_title' => 'Professional Digital Transformation Consultant',
            'about_section_subtitle' => 'Bridging Traditional Manufacturing with AI Innovation',
            'about_section_description' => '<p>With over 16+ years of experience in IT & technology, I specialize in helping manufacturing companies transform their operations through strategic technology implementation, automation solutions, and AI-driven innovation.</p>',
            'award_section_title' => 'Awards & Professional Recognition',
            'award_section_subtitle' => 'Innovative solutions that drive real business impact',
            'show_about_section' => 1,
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Professional consulting business settings updated');
        $this->command->info('📧 Contact email updated to: admin@alisadikin.com');
        $this->command->info('🏢 Business focus: Digital Transformation Consulting');
    }
}