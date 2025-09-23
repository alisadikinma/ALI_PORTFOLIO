<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed setting table
        DB::table('setting')->insert([
            'id_setting' => 1,
            'site_name' => 'ALI SADIKIN - Digital Transformation Consultant',
            'site_description' => 'Digital transformation expert specializing in manufacturing AI, automation, and consulting services.',
            'site_keywords' => 'digital transformation, manufacturing AI, automation, consulting, Industry 4.0',
            'site_author' => 'Ali Sadikin',
            'site_url' => 'http://localhost',
            'site_email' => 'ali@aliportfolio.com',
            'site_phone' => '+62 812-3456-7890',
            'site_address' => 'Jakarta, Indonesia',
            'site_logo' => 'logo.png',
            'site_favicon' => 'favicon.ico',
            'footer_text' => 'Digital Transformation Consultant - Empowering businesses through technology',
            'social_linkedin' => 'https://linkedin.com/in/alisadikin',
            'social_github' => 'https://github.com/alisadikin',
            'social_instagram' => 'https://instagram.com/alisadikin',
            'whatsapp_number' => '6281234567890',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Seed lookup_data table
        $lookupData = [
            // Homepage sections
            ['lookup_type' => 'homepage_section', 'lookup_code' => 'hero', 'lookup_name' => 'Hero Section', 'sort_order' => 1],
            ['lookup_type' => 'homepage_section', 'lookup_code' => 'about', 'lookup_name' => 'About Section', 'sort_order' => 2],
            ['lookup_type' => 'homepage_section', 'lookup_code' => 'services', 'lookup_name' => 'Services Section', 'sort_order' => 3],
            ['lookup_type' => 'homepage_section', 'lookup_code' => 'portfolio', 'lookup_name' => 'Portfolio Section', 'sort_order' => 4],
            ['lookup_type' => 'homepage_section', 'lookup_code' => 'testimonials', 'lookup_name' => 'Testimonials Section', 'sort_order' => 5],
            ['lookup_type' => 'homepage_section', 'lookup_code' => 'contact', 'lookup_name' => 'Contact Section', 'sort_order' => 6],

            // Project categories
            ['lookup_type' => 'project_category', 'lookup_code' => 'manufacturing_ai', 'lookup_name' => 'Manufacturing AI', 'lookup_icon' => 'robot', 'lookup_color' => '#8b5cf6'],
            ['lookup_type' => 'project_category', 'lookup_code' => 'automation', 'lookup_name' => 'Automation Systems', 'lookup_icon' => 'gear', 'lookup_color' => '#06b6d4'],
            ['lookup_type' => 'project_category', 'lookup_code' => 'digital_transformation', 'lookup_name' => 'Digital Transformation', 'lookup_icon' => 'digital', 'lookup_color' => '#ec4899'],
            ['lookup_type' => 'project_category', 'lookup_code' => 'consulting', 'lookup_name' => 'Business Consulting', 'lookup_icon' => 'consulting', 'lookup_color' => '#fbbf24'],
        ];

        foreach ($lookupData as $index => $data) {
            DB::table('lookup_data')->insert([
                'id_lookup_data' => $index + 1,
                'lookup_type' => $data['lookup_type'],
                'lookup_code' => $data['lookup_code'],
                'lookup_name' => $data['lookup_name'],
                'lookup_icon' => $data['lookup_icon'] ?? null,
                'lookup_color' => $data['lookup_color'] ?? null,
                'sort_order' => $data['sort_order'] ?? 999,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Seed sample projects
        $projects = [
            [
                'project_name' => 'Smart Manufacturing AI System',
                'client_name' => 'PT Manufacturing Excellence',
                'location' => 'Jakarta, Indonesia',
                'description' => 'AI-powered manufacturing optimization system that increased efficiency by 40%',
                'summary_description' => 'Revolutionary AI system for manufacturing optimization',
                'category_lookup_id' => 7, // Manufacturing AI
                'slug_project' => 'smart-manufacturing-ai-system',
                'technologies_used' => json_encode(['Python', 'TensorFlow', 'IoT', 'Cloud Computing']),
                'project_year' => 2024,
                'is_featured' => true,
                'sequence' => 1
            ],
            [
                'project_name' => 'Digital Transformation Strategy',
                'client_name' => 'Global Tech Corporation',
                'location' => 'Singapore',
                'description' => 'Complete digital transformation roadmap and implementation',
                'summary_description' => 'Strategic digital transformation for enterprise growth',
                'category_lookup_id' => 9, // Digital Transformation
                'slug_project' => 'digital-transformation-strategy',
                'technologies_used' => json_encode(['Cloud Migration', 'API Integration', 'Data Analytics']),
                'project_year' => 2024,
                'is_featured' => true,
                'sequence' => 2
            ],
            [
                'project_name' => 'Automation Control System',
                'client_name' => 'Industrial Automation Ltd',
                'location' => 'Bandung, Indonesia',
                'description' => 'Advanced automation system for industrial processes',
                'summary_description' => 'Next-generation industrial automation solution',
                'category_lookup_id' => 8, // Automation
                'slug_project' => 'automation-control-system',
                'technologies_used' => json_encode(['PLC', 'SCADA', 'HMI', 'Industrial IoT']),
                'project_year' => 2023,
                'is_featured' => false,
                'sequence' => 3
            ]
        ];

        foreach ($projects as $index => $project) {
            DB::table('project')->insert([
                'id_project' => $index + 1,
                'project_name' => $project['project_name'],
                'client_name' => $project['client_name'],
                'location' => $project['location'],
                'description' => $project['description'],
                'summary_description' => $project['summary_description'],
                'category_lookup_id' => $project['category_lookup_id'],
                'slug_project' => $project['slug_project'],
                'technologies_used' => $project['technologies_used'],
                'project_year' => $project['project_year'],
                'status' => 'Active',
                'is_featured' => $project['is_featured'],
                'sequence' => $project['sequence'],
                'views_count' => rand(100, 1000),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Seed services
        $services = [
            [
                'nama_layanan' => 'Manufacturing AI Consulting',
                'deskripsi_layanan' => 'AI-powered solutions for manufacturing optimization',
                'icon_layanan' => 'robot',
                'sequence' => 1
            ],
            [
                'nama_layanan' => 'Digital Transformation Strategy',
                'deskripsi_layanan' => 'Complete digital transformation roadmap',
                'icon_layanan' => 'digital',
                'sequence' => 2
            ],
            [
                'nama_layanan' => 'Automation Systems',
                'deskripsi_layanan' => 'Industrial automation and control systems',
                'icon_layanan' => 'gear',
                'sequence' => 3
            ]
        ];

        foreach ($services as $index => $service) {
            DB::table('layanan')->insert([
                'id_layanan' => $index + 1,
                'nama_layanan' => $service['nama_layanan'],
                'deskripsi_layanan' => $service['deskripsi_layanan'],
                'icon_layanan' => $service['icon_layanan'],
                'status' => 'Active',
                'sequence' => $service['sequence'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Seed testimonials
        $testimonials = [
            [
                'client_name' => 'John Smith',
                'company_name' => 'Tech Manufacturing Inc',
                'position' => 'CEO',
                'deskripsi_testimonial' => 'Ali transformed our manufacturing process with AI. Incredible results!',
                'rating' => 5,
                'project_id' => 1
            ],
            [
                'client_name' => 'Sarah Johnson',
                'company_name' => 'Global Industries',
                'position' => 'CTO',
                'deskripsi_testimonial' => 'Outstanding digital transformation expertise. Highly recommended!',
                'rating' => 5,
                'project_id' => 2
            ]
        ];

        foreach ($testimonials as $index => $testimonial) {
            DB::table('testimonial')->insert([
                'id_testimonial' => $index + 1,
                'client_name' => $testimonial['client_name'],
                'company_name' => $testimonial['company_name'],
                'position' => $testimonial['position'],
                'deskripsi_testimonial' => $testimonial['deskripsi_testimonial'],
                'rating' => $testimonial['rating'],
                'project_id' => $testimonial['project_id'],
                'status' => 'Active',
                'display_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Admin user seeder
        if (class_exists(\App\Models\User::class)) {
            User::create([
                'name' => 'Ali Sadikin',
                'email' => 'admin@aliportfolio.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
