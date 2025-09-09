<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';
    protected $primaryKey = 'id_berita';

    protected $fillable = [
        'judul_berita',
        'meta_title',
        'meta_description',
        'tags',
        'reading_time',
        'featured_snippet',
        'conclusion',
        'focus_keyword',
        'faq_data',
        'is_featured',
        'views',
        'isi_berita',
        'gambar_berita',
        'tanggal_berita',
        'slug_berita',
        'kategori_berita',
        'related_ids',
    ];

    protected $casts = [
        'faq_data' => 'array',
        'is_featured' => 'boolean',
        'views' => 'integer',
        'reading_time' => 'integer',
        'tanggal_berita' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug_berita';
    }

    /**
     * Accessor + Mutator untuk related_ids
     * - GET: selalu kembalikan array
     * - SET: selalu simpan dalam JSON string
     */
    protected function relatedIds(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // null/empty -> []
                if (empty($value)) {
                    return [];
                }

                // jika sudah array, langsung kembalikan
                if (is_array($value)) {
                    return $value;
                }

                // coba decode JSON
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }

                // fallback: koma-separated "1,2,3"
                $parts = array_filter(array_map('trim', explode(',', (string)$value)), fn ($v) => $v !== '');
                return array_values($parts);
            },
            set: function ($value) {
                // Normalize ke array dulu
                if (is_null($value)) {
                    return null;
                }

                if (is_string($value)) {
                    // Coba treat sebagai JSON valid
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        return json_encode(array_values($decoded));
                    }
                    // Atau treat sebagai "1,2,3"
                    $parts = array_filter(array_map('trim', explode(',', $value)), fn ($v) => $v !== '');
                    return json_encode(array_values($parts));
                }

                if (is_array($value)) {
                    return json_encode(array_values($value));
                }

                // nilai tunggal
                return json_encode([$value]);
            }
        );
    }

    /**
     * Ambil koleksi Berita terkait berdasarkan related_ids.
     * Selalu kembalikan Collection, dan JANGAN panggil whereIn kalau kosong
     * untuk menghindari error count() di internal builder.
     */
    public function relatedBerita()
    {
        $ids = $this->related_ids ?? [];
        if (empty($ids)) {
            return collect();
        }

        // Pastikan elemennya scalar (string/int)
        $ids = array_values(array_map(fn ($v) => is_numeric($v) ? (int)$v : (string)$v, $ids));

        return self::whereIn('id_berita', $ids)->get();
    }

    /**
     * Scope for featured articles
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('tanggal_berita');
    }

    /**
     * Get excerpt for meta description
     */
    public function getExcerptAttribute()
    {
        if (!empty($this->meta_description)) {
            return $this->meta_description;
        }
        
        if (!empty($this->featured_snippet)) {
            return \Illuminate\Support\Str::limit($this->featured_snippet, 160);
        }
        
        return \Illuminate\Support\Str::limit(strip_tags($this->isi_berita), 160);
    }

    /**
     * Get full URL for article
     */
    public function getUrlAttribute()
    {
        return route('article.detail', $this->slug_berita);
    }
}
