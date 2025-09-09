<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    protected $fillable = [
        'nama_layanan',
        'sub_nama_layanan',
        'icon_layanan',
        'gambar_layanan',
        'keterangan_layanan',
        'slug_layanan',
        'sequence',
        'status',
    ];

    protected $casts = [
        'sequence' => 'integer',
    ];

    // Scope for active records
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    // Scope for ordered by sequence
    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence', 'asc');
    }
}
