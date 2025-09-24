<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $table = 'testimonial';
    protected $primaryKey = 'id_testimonial';
    protected $fillable = [
        'judul_testimonial',
        'jabatan',
        'deskripsi_testimonial',
        'gambar_testimonial',
    ];
}
