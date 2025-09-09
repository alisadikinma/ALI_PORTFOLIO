<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        // Hitung jumlah data dari masing-masing tabel
        $countProject = DB::table('project')->count();
        $countGaleri  = DB::table('galeri')->count();
        $countBerita  = DB::table('berita')->count();
        $countPesan   = DB::table('contacts')->count();

        // Ambil data pesan terbaru
        $contacts = Contact::latest()->take(10)->get();

        return view('dashboard.index', compact(
            'title',
            'countProject',
            'countGaleri',
            'countBerita',
            'countPesan',
            'contacts'
        ));
    }
}
