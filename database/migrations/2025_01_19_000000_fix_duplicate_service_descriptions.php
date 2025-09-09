<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update service descriptions to be unique
        $descriptions = [
            'Custom GPT' => 'I provide tailored AI solutions and custom GPT models designed to meet your business needs and industry requirements. From understanding your challenges to delivering a solution, I make sure the AI tools we create truly support your goals and make your processes smarter.',
            'Video' => 'Professional AI-powered video generation and content creation services. Transform your ideas into engaging visual content with automated video production, editing, and optimization for various platforms and purposes.',
            'Visual' => 'Advanced computer vision and AI-powered visual inspection systems. Automate quality control, detect defects, and ensure consistency in manufacturing processes with cutting-edge image recognition technology.',
            'Consultation' => 'Strategic AI consultation and technology speaking services. Get expert guidance on digital transformation, AI implementation strategies, and innovative solutions tailored to your industry and business objectives.',
            'Automation' => 'Comprehensive process automation and workflow optimization solutions. Streamline operations, reduce manual tasks, and increase efficiency with intelligent automation systems designed for your specific business needs.'
        ];

        foreach ($descriptions as $keyword => $description) {
            DB::table('layanan')
                ->where('nama_layanan', 'LIKE', "%{$keyword}%")
                ->where('status', 'Active')
                ->update(['keterangan_layanan' => $description]);
        }

        // If there are any remaining services without specific keywords, update them with sequence-based descriptions
        $services = DB::table('layanan')
            ->where('status', 'Active')
            ->orderBy('sequence')
            ->get();

        $fallbackDescriptions = [
            'I provide tailored AI solutions and custom GPT models designed to meet your business needs and industry requirements. From understanding your challenges to delivering a solution, I make sure the AI tools we create truly support your goals and make your processes smarter.',
            'Professional AI-powered video generation and content creation services. Transform your ideas into engaging visual content with automated video production, editing, and optimization for various platforms and purposes.',
            'Advanced computer vision and AI-powered visual inspection systems. Automate quality control, detect defects, and ensure consistency in manufacturing processes with cutting-edge image recognition technology.',
            'Strategic AI consultation and technology speaking services. Get expert guidance on digital transformation, AI implementation strategies, and innovative solutions tailored to your industry and business objectives.',
            'Comprehensive process automation and workflow optimization solutions. Streamline operations, reduce manual tasks, and increase efficiency with intelligent automation systems designed for your specific business needs.'
        ];

        foreach ($services as $index => $service) {
            if (empty(trim(strip_tags($service->keterangan_layanan)))) {
                DB::table('layanan')
                    ->where('id_layanan', $service->id_layanan)
                    ->update([
                        'keterangan_layanan' => $fallbackDescriptions[$index % count($fallbackDescriptions)]
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as we're fixing data
    }
};
