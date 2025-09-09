<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galeri';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_galeri';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_galeri',
        'company',
        'period',
        'deskripsi_galeri',
        'thumbnail',
        'sequence',
        'status'
    ];

    /**
     * Get the gallery items for the galeri.
     */
    public function items()
    {
        return $this->hasMany(GalleryItem::class, 'id_galeri', 'id_galeri')
            ->where('status', 'Active')
            ->orderBy('sequence');
    }

    /**
     * Get the gallery items for the galeri (alias for backward compatibility).
     */
    public function galleryItems()
    {
        return $this->hasMany(GalleryItem::class, 'id_galeri', 'id_galeri')
            ->orderBy('sequence');
    }

    /**
     * Get only active gallery items (for homepage)
     */
    public function activeGalleryItems()
    {
        return $this->hasMany(GalleryItem::class, 'id_galeri', 'id_galeri')
            ->where('status', 'Active')
            ->orderBy('sequence');
    }

    /**
     * Scope a query to only include active galeri.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Get the thumbnail URL.
     *
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/placeholder.jpg');
    }
}
