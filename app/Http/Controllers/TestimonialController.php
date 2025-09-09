<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Testimonial';
        $testimonial = DB::table('testimonial')->orderByDesc('id_testimonial')->get();
        return view('testimonial.index', compact('title', 'testimonial'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Testimonial';
        return view('testimonial.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib diisi!!!',
        ];
        $request->validate([
            'judul_testimonial' => 'required',
            'jabatan' => 'required',
            'deskripsi_testimonial' => 'required',
            'gambar_testimonial' => 'required',
        ],$messages);
        $gambar_testimonial = $request->file('gambar_testimonial');
        $namagambartestimonial = 'Testimonial'.date('Ymdhis').'.'.$request->file('gambar_testimonial')->getClientOriginalExtension();
        $gambar_testimonial->move('file/testimonial/',$namagambartestimonial);

        $data = new Testimonial();
        $data->judul_testimonial = $request->judul_testimonial;
        $data->jabatan = $request->jabatan;
        $data->deskripsi_testimonial = $request->deskripsi_testimonial;
        $data->gambar_testimonial = $namagambartestimonial;
        $data->save();
        return redirect()->route('testimonial.index')->with('Sukses', 'Berhasil Tambah Testimonial');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        $title = 'Edit Testimonial';
        return view('testimonial.edit',compact('testimonial', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $namagambartestimonial = $testimonial->gambar_testimonial;
        $update = [
            'judul_testimonial' => $request->judul_testimonial,
            'jabatan' => $request->jabatan,
            'deskripsi_testimonial' => $request->deskripsi_testimonial,
            'gambar_testimonial' => $namagambartestimonial,
        ];
        if ($request->gambar_testimonial != ""){
            $request->gambar_testimonial->move(public_path('file/testimonial/'), $namagambartestimonial);
        }
        $testimonial->update($update);
        return redirect()->route('testimonial.index')->with('Sukses', 'Berhasil Update Testimonial');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $namagambartestimonial = $testimonial->gambar_testimonial;
        $gambar_testimonial = ('file/testimonial/').$namagambartestimonial;
        if(file_exists($gambar_testimonial)){
            @unlink($gambar_testimonial);
        }
        $testimonial->delete();
        return redirect()->back()->with('Sukses', 'Berhasil Hapus Testimonial');
    }
}
