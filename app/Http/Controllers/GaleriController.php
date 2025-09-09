<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\GalleryItem;
use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GaleriController extends Controller
{
    public function index()
    {
        $title = 'Data Galeri';
        $galeri = Galeri::with(['galleryItems' => function($query) {
            $query->orderBy('sequence', 'asc');
        }])
        ->orderBy('sequence', 'asc')
        ->orderByDesc('id_galeri')
        ->get();
        
        return view('galeri.index', compact('galeri', 'title'));
    }

    public function create()
    {
        $title = 'Tambah Galeri';
        $awards = Award::orderBy('nama_award', 'asc')->get();
        return view('galeri.create', compact('title', 'awards'));
    }

    public function store(Request $request)
    {
        // Debug: Log all request data
        Log::info('Gallery Store Request Data:', $request->all());
        
        // Basic validation first
        $request->validate([
            'nama_galeri' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'period' => 'nullable|string|max:255',
            'deskripsi_galeri' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sequence' => 'nullable|integer|min:0',
            'status' => 'required|in:Active,Inactive',
            'id_award' => 'nullable|exists:award,id_award',
        ]);

        DB::beginTransaction();

        try {
            // Create gallery first
            $galeriData = [
                'nama_galeri' => $request->nama_galeri,
                'company' => $request->company,
                'period' => $request->period,
                'deskripsi_galeri' => $request->deskripsi_galeri,
                'sequence' => $request->sequence ?? 0,
                'status' => $request->status ?? 'Active',
            ];

            // Upload thumbnail
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $thumbnailName = 'thumb_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Ensure directory exists
                $uploadPath = public_path('file/galeri');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $thumbnailName);
                $galeriData['thumbnail'] = $thumbnailName;
            }

            $galeri = Galeri::create($galeriData);
            Log::info('Gallery created successfully:', ['id' => $galeri->id_galeri]);

            // Process gallery items if they exist
            if ($request->has('gallery_items') && is_array($request->gallery_items)) {
                Log::info('Processing gallery items:', $request->gallery_items);
                
                foreach ($request->gallery_items as $index => $item) {
                    // Skip empty items
                    if (empty($item['type'])) {
                        Log::info('Skipping empty item at index:', [$index]);
                        continue;
                    }

                    $itemData = [
                        'id_galeri' => $galeri->id_galeri,
                        'type' => $item['type'],
                        'id_award' => $request->id_award, // Award dari galeri, bukan per item
                        'sequence' => $item['sequence'] ?? $index,
                        'status' => 'Active', // Default status
                    ];

                    // Handle file upload for image type
                    if ($item['type'] === 'image' && isset($item['file']) && $item['file']) {
                        try {
                            $file = $item['file'];
                            $filename = 'gallery_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                            
                            $uploadPath = public_path('file/galeri');
                            if (!file_exists($uploadPath)) {
                                mkdir($uploadPath, 0755, true);
                            }
                            
                            $file->move($uploadPath, $filename);
                            $itemData['file_name'] = $filename;
                            Log::info('File uploaded successfully:', ['filename' => $filename]);
                        } catch (\Exception $e) {
                            Log::error('File upload failed:', ['error' => $e->getMessage()]);
                            throw new \Exception('Failed to upload file: ' . $e->getMessage());
                        }
                    }

                    // Handle YouTube URL
                    if ($item['type'] === 'youtube' && isset($item['youtube_url']) && !empty($item['youtube_url'])) {
                        $itemData['youtube_url'] = $item['youtube_url'];
                    }

                    // Validate that required data exists based on type
                    if ($item['type'] === 'image' && !isset($itemData['file_name'])) {
                        Log::warning('Image type item without file, skipping:', $itemData);
                        continue;
                    }

                    if ($item['type'] === 'youtube' && !isset($itemData['youtube_url'])) {
                        Log::warning('YouTube type item without URL, skipping:', $itemData);
                        continue;
                    }

                    $galleryItem = GalleryItem::create($itemData);
                    Log::info('Gallery item created:', ['item_id' => $galleryItem->id_gallery_item, 'type' => $item['type']]);
                }
            } else {
                Log::info('No gallery items to process');
            }

            DB::commit();
            Log::info('Gallery saved successfully with ID:', [$galeri->id_galeri]);
            
            return redirect()->route('galeri.index')->with('Sukses', 'Berhasil Tambah Galeri');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gallery save failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('Error', 'Gagal menambah galeri: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Galeri $galeri)
    {
        $title = 'Edit Galeri';
        $awards = Award::orderBy('nama_award', 'asc')->get();
        $galeri->load(['galleryItems' => function($query) {
            $query->orderBy('id_gallery_item', 'asc');
        }]);
        
        return view('galeri.edit', compact('title', 'galeri', 'awards'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        // Debug: Log all request data
        Log::info('Gallery Update Request Data:', $request->all());
        
        // Basic validation first
        $request->validate([
            'nama_galeri' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'period' => 'nullable|string|max:255',
            'deskripsi_galeri' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sequence' => 'nullable|integer|min:0',
            'status' => 'required|in:Active,Inactive',
            'id_award' => 'nullable|exists:award,id_award',
        ]);

        DB::beginTransaction();

        try {
            // Update gallery
            $galeriData = [
                'nama_galeri' => $request->nama_galeri,
                'company' => $request->company,
                'period' => $request->period,
                'deskripsi_galeri' => $request->deskripsi_galeri,
                'sequence' => $request->sequence ?? 0,
                'status' => $request->status ?? 'Active',
            ];

            // Update thumbnail
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($galeri->thumbnail && file_exists(public_path('file/galeri/' . $galeri->thumbnail))) {
                    unlink(public_path('file/galeri/' . $galeri->thumbnail));
                }

                $file = $request->file('thumbnail');
                $thumbnailName = 'thumb_' . time() . '.' . $file->getClientOriginalExtension();
                
                $uploadPath = public_path('file/galeri');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $thumbnailName);
                $galeriData['thumbnail'] = $thumbnailName;
            }

            $galeri->update($galeriData);

            // Delete existing gallery items and their files
            foreach ($galeri->galleryItems as $item) {
                if ($item->file_name && $item->type === 'image') {
                    $filePath = public_path('file/galeri/' . $item->file_name);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                $item->delete();
            }

            // Process new gallery items
            if ($request->has('gallery_items') && is_array($request->gallery_items)) {
                foreach ($request->gallery_items as $index => $item) {
                    if (empty($item['type'])) continue;

                    $itemData = [
                        'id_galeri' => $galeri->id_galeri,
                        'type' => $item['type'],
                        'id_award' => $request->id_award, // Award dari galeri, bukan per item
                        'sequence' => $item['sequence'] ?? $index,
                        'status' => 'Active', // Default status
                    ];

                    // Handle file upload for image
                    if ($item['type'] === 'image' && isset($item['file']) && $item['file']) {
                        $file = $item['file'];
                        $filename = 'gallery_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                        
                        $uploadPath = public_path('file/galeri');
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }
                        
                        $file->move($uploadPath, $filename);
                        $itemData['file_name'] = $filename;
                    }

                    // Handle YouTube URL
                    if ($item['type'] === 'youtube' && isset($item['youtube_url']) && !empty($item['youtube_url'])) {
                        $itemData['youtube_url'] = $item['youtube_url'];
                    }

                    // Validate that required data exists based on type
                    if ($item['type'] === 'image' && !isset($itemData['file_name'])) {
                        continue;
                    }

                    if ($item['type'] === 'youtube' && !isset($itemData['youtube_url'])) {
                        continue;
                    }

                    GalleryItem::create($itemData);
                }
            }

            DB::commit();
            return redirect()->route('galeri.index')->with('Sukses', 'Berhasil Edit Galeri');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gallery update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('Error', 'Gagal mengupdate galeri: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete thumbnail
            if ($galeri->thumbnail && file_exists(public_path('file/galeri/' . $galeri->thumbnail))) {
                unlink(public_path('file/galeri/' . $galeri->thumbnail));
            }

            // Delete gallery items and their files
            foreach ($galeri->galleryItems as $item) {
                if ($item->file_name && $item->type === 'image') {
                    $filePath = public_path('file/galeri/' . $item->file_name);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                $item->delete();
            }

            $galeri->delete();

            DB::commit();
            return redirect()->back()->with('Sukses', 'Berhasil Hapus Galeri');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('Error', 'Gagal menghapus galeri: ' . $e->getMessage());
        }
    }

    public function storeImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('images'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/' . $fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    // API endpoint for getting gallery items by award
    public function getGalleryByAward($awardId)
    {
        $galleryItems = GalleryItem::with(['galeri'])
            ->where('id_award', $awardId)
            ->orderBy('id_gallery_item', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $galleryItems->map(function($item) {
                return [
                    'id' => $item->id_gallery_item,
                    'type' => $item->type,
                    'file_url' => $item->file_url,
                    'thumbnail_url' => $item->thumbnail_url,
                    'gallery_name' => $item->galeri->nama_galeri,
                ];
            })
        ]);
    }

    /**
     * Show a specific gallery item for modal (AJAX)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showItem($id)
    {
        try {
            $item = GalleryItem::with('galeri')
                ->where('id_gallery_item', $id)
                ->where('status', 'Active')
                ->first();

            if (!$item) {
                return response()->json([
                    'error' => 'Item not found or inactive'
                ], 404);
            }

            $response = [
                'id' => $item->id_gallery_item,
                'type' => $item->type,
                'title' => $item->galeri->nama_galeri ?? 'Gallery Item',
                'galeri' => [
                    'id_galeri' => $item->galeri->id_galeri,
                    'nama_galeri' => $item->galeri->nama_galeri,
                    'company' => $item->galeri->company ?? '—',
                    'period' => $item->galeri->period ?? '—',
                    'deskripsi_galeri' => $item->galeri->deskripsi_galeri ?? ''
                ],
                'image_url' => null,
                'video_url' => null,
                'youtube_embed' => null
            ];

            // Set media URLs based on type
            if ($item->type === 'image' && $item->file_name) {
                $response['image_url'] = asset('file/galeri/' . $item->file_name);
            } elseif ($item->type === 'video' && $item->file_name) {
                $response['video_url'] = asset('file/galeri/' . $item->file_name);
            } elseif ($item->type === 'youtube' && $item->youtube_url) {
                $response['youtube_embed'] = $this->convertYoutubeToEmbed($item->youtube_url);
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error fetching gallery item:', [
                'item_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Show a specific gallery with its items
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $galeri = Galeri::with(['items' => function($query) {
            $query->where('status', 'Active')
                  ->orderBy('sequence')
                  ->orderBy('id_gallery_item');
        }])
        ->where('id_galeri', $id)
        ->where('status', 'Active')
        ->firstOrFail();

        $title = $galeri->nama_galeri;

        return view('galeri.show', compact('galeri', 'title'));
    }

    /**
     * Convert YouTube URL to embed format
     *
     * @param string $url
     * @return string
     */
    private function convertYoutubeToEmbed($url)
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
