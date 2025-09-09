<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'setting';
    protected $primaryKey = 'id_setting';
    protected $fillable = [
        'instansi_setting',
        'pimpinan_setting',
        'logo_setting',
        'favicon_setting',
        'tentang_setting',
        'misi_setting',
        'visi_setting',
        'keyword_setting',
        'alamat_setting',
        'instagram_setting',
        'youtube_setting',
        'email_setting',
        'tiktok_setting',
        'facebook_setting',
        'linkedin_setting',
        'no_hp_setting',
        'maps_setting',
        'profile_title',
        'profile_content',
        'primary_button_title',
        'primary_button_link',
        'secondary_button_title',
        'secondary_button_link',
        'years_experience',
        'followers_count',
        'project_delivered',
        'cost_savings',
        'success_rate',
        'about_section_title',
        'about_section_subtitle',
        'about_section_description',
        'about_section_image',
        'award_section_title',
        'award_section_subtitle',
        'about_section_active',
        'services_section_active',
        'portfolio_section_active',
        'testimonials_section_active',
        'gallery_section_active',
        'articles_section_active',
        'awards_section_active',
        'contact_section_active',
        'background_setting',
        'bg_tentang_setting',
    ];
}