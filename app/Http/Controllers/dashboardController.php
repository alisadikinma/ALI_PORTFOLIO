<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Contact;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        try {
            // Cache dashboard stats for 5 minutes for better performance
            $stats = Cache::remember('dashboard_stats', 300, function () {
                return [
                    'countProject' => DB::table('project')->count(),
                    'countGaleri' => DB::table('galeri')->count(),
                    'countBerita' => DB::table('berita')->count(), // Removed status filter
                    'countPesan' => DB::table('contacts')->count()
                ];
            });

            // Cache recent contacts for 1 minute for better performance
            $contacts = Cache::remember('recent_contacts', 60, function () {
                return Contact::select(['id', 'name', 'email', 'created_at'])
                             ->latest()
                             ->take(10)
                             ->get();
            });

            return view('dashboard.index', array_merge(
                ['title' => $title, 'contacts' => $contacts],
                $stats
            ));
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Dashboard Error: ' . $e->getMessage());
            
            // Return view dengan data default jika error
            return view('dashboard.index', [
                'title' => $title,
                'countProject' => 0,
                'countGaleri' => 0,
                'countBerita' => 0,
                'countPesan' => 0,
                'contacts' => collect()
            ]);
        }
    }
}
