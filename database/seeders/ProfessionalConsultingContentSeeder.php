<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProfessionalConsultingContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populate database with professional consulting portfolio content
     */
    public function run(): void
    {
        $this->updateSettings();
        $this->seedProfessionalProjects();
        $this->updateLookupData();
    }

    /**
     * Update settings with professional consulting profile
     */
    private function updateSettings(): void
    {
        // Use only columns that definitely exist in the settings table
        $settingData = [
            'instansi_setting' => 'Ali Digital Transformation Consulting',
            'pimpinan_setting' => 'Ali Sadikin, M.A.',
            'keyword_setting' => 'digital transformation consultant, manufacturing automation, industry 4.0, business intelligence, ERP implementation',
            'alamat_setting' => 'Jakarta, Indonesia',
            'email_setting' => 'ali@digitaltransformation.consulting',
            'no_hp_setting' => '+62 812-3456-7890',
            'linkedin_setting' => 'https://linkedin.com/in/ali-digital-transformation',
            'instagram_setting' => 'https://instagram.com/ali_digital_transformation',
            'youtube_setting' => 'https://youtube.com/@AliDigitalTransformation',
            'facebook_setting' => 'https://facebook.com/ali.digitaltransformation',
            'tiktok_setting' => 'https://tiktok.com/@ali.digitaltransformation',
            'years_experience' => 8,
            'followers_count' => '54K+',
            'project_delivered' => 150,
        ];

        // Add only fields that exist in the table structure
        if (Schema::hasColumn('setting', 'profile_title')) {
            $settingData['profile_title'] = 'Digital Transformation Consultant';
            $settingData['profile_content'] = 'Transforming Manufacturing Through Digital Innovation | 54K+ Community | 150+ Projects Delivered';
        }

        if (Schema::hasColumn('setting', 'primary_button_title')) {
            $settingData['primary_button_title'] = 'View Portfolio';
            $settingData['primary_button_link'] = '/portfolio';
            $settingData['secondary_button_title'] = 'Book Consultation';
            $settingData['secondary_button_link'] = '/contact';
        }

        if (Schema::hasColumn('setting', 'about_section_title')) {
            $settingData['about_section_title'] = 'About Ali';
            $settingData['about_section_subtitle'] = 'Digital Transformation Expert';
            $settingData['about_section_description'] = 'Digital Transformation Consultant with 8+ years of experience helping manufacturing companies modernize their operations through strategic technology implementation.';
        }

        if (Schema::hasColumn('setting', 'cost_savings')) {
            $settingData['cost_savings'] = '40%';
        }

        if (Schema::hasColumn('setting', 'success_rate')) {
            $settingData['success_rate'] = 98;
        }

        if (Schema::hasColumn('setting', 'show_about_section')) {
            $settingData['show_about_section'] = 1;
        }

        // Update or create settings record
        $setting = Setting::first();
        if ($setting) {
            $setting->update($settingData);
        } else {
            $settingData['id_setting'] = 1;
            Setting::create($settingData);
        }
    }

    /**
     * Seed professional consulting projects
     */
    private function seedProfessionalProjects(): void
    {
        $projects = [
            [
                'project_name' => 'Smart Manufacturing Implementation - PT Automotive Prima',
                'client_name' => 'PT Automotive Prima',
                'location' => 'Karawang, West Java',
                'images' => json_encode(['manufacturing-1.jpg', 'iot-sensors-1.jpg', 'dashboard-1.jpg']),
                'featured_image' => 'manufacturing-featured-1.jpg',
                'summary_description' => 'Comprehensive digital transformation of automotive parts manufacturing facility, implementing IoT sensors, real-time monitoring, and predictive maintenance systems.',
                'description' => 'Led the complete digital transformation of PT Automotive Prima\'s manufacturing facility, serving tier-1 automotive suppliers. The project encompassed implementation of Industry 4.0 technologies including IoT sensor networks, real-time production monitoring dashboards, and AI-powered predictive maintenance systems.

Key deliverables included:
• Installation of 200+ IoT sensors across production lines
• Implementation of real-time OEE monitoring system
• Development of predictive maintenance algorithms
• Integration with existing ERP systems
• Staff training and change management programs

The transformation resulted in 35% improvement in overall equipment effectiveness (OEE), 40% reduction in unplanned downtime, and 25% improvement in product quality metrics.',
                // Remove info_project as it doesn't exist in current table
                'project_category' => 'digital_transformation',
                'industry' => 'automotive',
                'url_project' => 'https://case-study.ali-consulting.com/automotive-prima',
                'budget_range' => '$500K - $1M',
                'duration_months' => 18,
                'roi_percentage' => 240.00,
                'technologies' => json_encode(['IoT', 'AWS Cloud', 'Apache Kafka', 'TensorFlow', 'PowerBI', 'Python', 'React', 'Node.js']),
                'key_results' => json_encode([
                    '35% improvement in Overall Equipment Effectiveness',
                    '40% reduction in unplanned downtime',
                    '25% improvement in product quality',
                    '30% reduction in maintenance costs',
                    '20% increase in production capacity',
                    'ROI achieved in 14 months'
                ]),
                'status' => 'Active',
                'is_featured' => true,
                'sequence' => 1,
                'views' => 450
            ],
            [
                'project_name' => 'ERP Digital Transformation - CV Elektronik Sejahtera',
                'client_name' => 'CV Elektronik Sejahtera',
                'location' => 'Surabaya, East Java',
                'images' => json_encode(['erp-system-1.jpg', 'dashboard-erp-1.jpg', 'training-1.jpg']),
                'featured_image' => 'erp-featured-1.jpg',
                'summary_description' => 'Complete ERP system overhaul and business process digitalization for electronics manufacturing company, achieving 45% improvement in operational efficiency.',
                'description' => 'Spearheaded comprehensive ERP transformation for CV Elektronik Sejahtera, a leading electronics manufacturer. The project involved complete replacement of legacy systems with modern, integrated ERP solution coupled with business process reengineering.

Project scope included:
• Legacy system audit and data migration
• Business process mapping and optimization
• Custom ERP implementation with manufacturing modules
• Supply chain integration and vendor portal development
• Financial reporting and analytics dashboard
• Mobile applications for inventory and quality management

Results achieved:
• 45% improvement in operational efficiency
• 60% reduction in inventory holding costs
• 50% faster order processing time
• 99.5% system uptime achievement
• Complete elimination of manual reporting',
                // Remove info_project as it doesn't exist in current table
                'project_category' => 'business_intelligence',
                'industry' => 'manufacturing',
                'url_project' => 'https://case-study.ali-consulting.com/elektronik-sejahtera',
                'budget_range' => '$300K - $500K',
                'duration_months' => 14,
                'roi_percentage' => 180.00,
                'technologies' => json_encode(['SAP Business One', 'SQL Server', 'PowerBI', 'Azure Cloud', 'REST APIs', 'Angular', 'C#', '.NET Core']),
                'key_results' => json_encode([
                    '45% improvement in operational efficiency',
                    '60% reduction in inventory holding costs',
                    '50% faster order processing time',
                    '99.5% system uptime achievement',
                    'Complete elimination of manual reporting'
                ]),
                'status' => 'Active',
                'is_featured' => true,
                'sequence' => 2,
                'views' => 320
            ],
            [
                'project_name' => 'Supply Chain Digitalization - Grup Tekstil Nusantara',
                'client_name' => 'Grup Tekstil Nusantara',
                'location' => 'Bandung, West Java',
                'images' => json_encode(['supply-chain-1.jpg', 'blockchain-1.jpg', 'textile-factory-1.jpg']),
                'featured_image' => 'supply-chain-featured-1.jpg',
                'summary_description' => 'End-to-end supply chain digitalization for textile manufacturing group, implementing blockchain traceability and AI-powered demand forecasting.',
                'description' => 'Designed and implemented comprehensive supply chain digitalization solution for Grup Tekstil Nusantara, one of Indonesia\'s largest textile manufacturers. The project focused on creating transparent, efficient, and sustainable supply chain operations.

Key components delivered:
• Blockchain-based supply chain traceability system
• AI-powered demand forecasting and inventory optimization
• Supplier portal with real-time collaboration tools
• Quality management system with IoT integration
• Sustainability reporting and ESG compliance dashboard
• Mobile applications for field operations and auditing

Transformation outcomes:
• 30% reduction in supply chain costs
• 95% improvement in demand forecast accuracy
• 50% faster supplier onboarding process
• 100% traceability from raw materials to finished products
• 25% improvement in on-time delivery performance',
                // Remove info_project as it doesn't exist in current table
                'project_category' => 'digital_transformation',
                'industry' => 'manufacturing',
                'url_project' => 'https://case-study.ali-consulting.com/tekstil-nusantara',
                'budget_range' => '$750K - $1.5M',
                'duration_months' => 24,
                'roi_percentage' => 320.00,
                'technologies' => json_encode(['Hyperledger Fabric', 'TensorFlow', 'Apache Spark', 'AWS', 'React Native', 'Node.js', 'MongoDB', 'Redis']),
                'key_results' => json_encode([
                    '30% reduction in supply chain costs',
                    '95% improvement in demand forecast accuracy',
                    '50% faster supplier onboarding process',
                    '100% traceability from raw materials to finished products',
                    '25% improvement in on-time delivery performance',
                    '$15M reduction in working capital requirements'
                ]),
                'status' => 'Active',
                'is_featured' => true,
                'sequence' => 3,
                'views' => 280
            ]
        ];

        foreach ($projects as $projectData) {
            // Check if project already exists
            $exists = Project::where('project_name', $projectData['project_name'])->exists();

            if (!$exists) {
                Project::create(array_merge($projectData, [
                    'slug_project' => Str::slug($projectData['project_name'])
                ]));
            }
        }
    }

    /**
     * Update lookup data with professional categories
     */
    private function updateLookupData(): void
    {
        if (!DB::table('lookup_data')->where('lookup_type', 'homepage_sections')->exists()) {
            $homepageSections = [
                [
                    'lookup_type' => 'homepage_sections',
                    'lookup_code' => 'hero',
                    'lookup_name' => 'Hero Section',
                    'lookup_description' => 'Main banner with consulting expertise introduction',
                    'is_active' => true,
                    'sort_order' => 1
                ],
                [
                    'lookup_type' => 'homepage_sections',
                    'lookup_code' => 'about',
                    'lookup_name' => 'About Section',
                    'lookup_description' => 'Professional consulting background and expertise',
                    'is_active' => true,
                    'sort_order' => 2
                ],
                [
                    'lookup_type' => 'homepage_sections',
                    'lookup_code' => 'services',
                    'lookup_name' => 'Services Section',
                    'lookup_description' => 'Digital transformation consulting services',
                    'is_active' => true,
                    'sort_order' => 3
                ],
                [
                    'lookup_type' => 'homepage_sections',
                    'lookup_code' => 'portfolio',
                    'lookup_name' => 'Portfolio Section',
                    'lookup_description' => 'Featured consulting projects and case studies',
                    'is_active' => true,
                    'sort_order' => 4
                ],
                [
                    'lookup_type' => 'homepage_sections',
                    'lookup_code' => 'achievements',
                    'lookup_name' => 'Achievements Section',
                    'lookup_description' => 'Professional achievements and statistics',
                    'is_active' => true,
                    'sort_order' => 5
                ],
                [
                    'lookup_type' => 'homepage_sections',
                    'lookup_code' => 'testimonials',
                    'lookup_name' => 'Testimonials Section',
                    'lookup_description' => 'Client testimonials and success stories',
                    'is_active' => true,
                    'sort_order' => 6
                ],
                [
                    'lookup_type' => 'homepage_sections',
                    'lookup_code' => 'contact',
                    'lookup_name' => 'Contact Section',
                    'lookup_description' => 'Contact information and consultation booking',
                    'is_active' => true,
                    'sort_order' => 7
                ]
            ];

            foreach ($homepageSections as $section) {
                DB::table('lookup_data')->insert(array_merge($section, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }

        // Add professional consulting categories
        $categories = [
            [
                'lookup_type' => 'project_category',
                'lookup_code' => 'digital_transformation',
                'lookup_name' => 'Digital Transformation',
                'lookup_description' => 'Digital transformation consulting projects',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'lookup_type' => 'project_category',
                'lookup_code' => 'manufacturing_automation',
                'lookup_name' => 'Manufacturing Automation',
                'lookup_description' => 'Manufacturing process automation and optimization',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'lookup_type' => 'project_category',
                'lookup_code' => 'business_intelligence',
                'lookup_name' => 'Business Intelligence',
                'lookup_description' => 'BI and data analytics implementation',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'lookup_type' => 'industry',
                'lookup_code' => 'manufacturing',
                'lookup_name' => 'Manufacturing',
                'lookup_description' => 'Manufacturing industry projects',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'lookup_type' => 'industry',
                'lookup_code' => 'automotive',
                'lookup_name' => 'Automotive',
                'lookup_description' => 'Automotive industry projects',
                'is_active' => true,
                'sort_order' => 2
            ]
        ];

        foreach ($categories as $category) {
            $exists = DB::table('lookup_data')
                ->where('lookup_type', $category['lookup_type'])
                ->where('lookup_code', $category['lookup_code'])
                ->exists();

            if (!$exists) {
                DB::table('lookup_data')->insert(array_merge($category, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }
    }
}