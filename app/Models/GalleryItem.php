<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gallery_items';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_gallery_item';

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
        'id_galeri',
        'type',
        'file_name',
        'youtube_url',
        'id_award',
        'sequence',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id_award' => 'integer',
        'sequence' => 'integer',
    ];

    /**
     * Get the galeri that owns the gallery item.
     */
    public function galeri()
    {
        return $this->belongsTo(Galeri::class, 'id_galeri', 'id_galeri');
    }

    /**
     * Scope a query to only include active items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Get the full URL for the file.
     *
     * @return string|null
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_name) {
            return Storage::url('gallery_items/' . $this->file_name);
        }
        return null;
    }

    /**
     * Get the YouTube embed URL.
     *
     * @return string|null
     */
    public function getYoutubeEmbedUrlAttribute()
    {
        if ($this->type === 'youtube' && $this->youtube_url) {
            return $this->convertYoutubeToEmbed($this->youtube_url);
        }
        return null;
    }

    /**
     * Convert YouTube URL to embed format.
     *
     * @param string $url
     * @return string
     */
    protected function convertYoutubeToEmbed($url)
    {
        // Handle various YouTube URL formats
        $videoId = null;
        
        // Check for youtube.com/watch?v= format
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Check for youtu.be/ format
        elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Check for youtube.com/embed/ format (already embed)
        elseif (preg_match('/youtube\.com\/embed\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        
        if ($videoId) {
            return 'https://www.youtube.com/embed/' . $videoId;
        }
        
        return $url;
    }
}
