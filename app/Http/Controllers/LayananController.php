<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Layanan';
        // Use Eloquent model instead of DB table to get all fields including sub_nama_layanan
        $layanan = Layanan::orderBy('sequence', 'asc')
            ->orderByDesc('id_layanan')
            ->get();
        
        return view('layanan.index', compact('layanan', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Layanan';
        return view('layanan.create', compact('title'));
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
            'nama_layanan' => 'required',
            'gambar_layanan' => 'required',
            'keterangan_layanan' => 'required',
        ],$messages);
        $gambar_layanan = $request->file('gambar_layanan');
        $namagambarlayanan = 'layanan'.date('Ymdhis').'.'.$request->file('gambar_layanan')->getClientOriginalExtension();
        $gambar_layanan->move('file/layanan/',$namagambarlayanan);

        // Handle icon upload
        $namaicon = null;
        if ($request->hasFile('icon_layanan')) {
            $icon_layanan = $request->file('icon_layanan');
            $namaicon = 'icon_layanan'.date('Ymdhis').'.'.$icon_layanan->getClientOriginalExtension();
            $icon_layanan->move('file/layanan/icons/',$namaicon);
        }

        $data = new layanan();
        $data->nama_layanan = $request->nama_layanan;
        $data->sub_nama_layanan = $request->sub_nama_layanan;
        $data->icon_layanan = $namaicon;
        $data->gambar_layanan = $namagambarlayanan;
        $data->keterangan_layanan = $request->keterangan_layanan;
        $data->slug_layanan = Str::slug($request->nama_layanan);
        $data->sequence = $request->sequence ?? 0;
        $data->status = $request->status ?? 'Active';
        $data->save();
        return redirect()->route('layanan.index')->with('Sukses', 'Berhasil Tambah Layanan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Layanan $layanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $layanan = Layanan::find($id);
        $title = 'Edit Layanan';
        return view('layanan.edit', compact('layanan', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $layanan = Layanan::find($id);
        $namagambarlayanan = $layanan->gambar_layanan;
        $namaicon = $layanan->icon_layanan;
        
        // Handle icon upload
        if ($request->hasFile('icon_layanan')) {
            // Delete old icon if exists
            if ($namaicon && file_exists(public_path('file/layanan/icons/' . $namaicon))) {
                @unlink(public_path('file/layanan/icons/' . $namaicon));
            }
            $icon_layanan = $request->file('icon_layanan');
            $namaicon = 'icon_layanan'.date('Ymdhis').'.'.$icon_layanan->getClientOriginalExtension();
            $icon_layanan->move('file/layanan/icons/',$namaicon);
        }
        
        $update = [
            'nama_layanan' => $request->nama_layanan,
            'sub_nama_layanan' => $request->sub_nama_layanan,
            'icon_layanan' => $namaicon,
            'gambar_layanan' => $namagambarlayanan,
            'keterangan_layanan' => $request->keterangan_layanan,
            'slug_layanan' => Str::slug($request->nama_layanan),
            'sequence' => $request->sequence ?? 0,
            'status' => $request->status ?? 'Active',
        ];
        if ($request->gambar_layanan != ""){
            $request->gambar_layanan->move(public_path('file/layanan/'), $namagambarlayanan);
        }   
        $layanan->update($update);
        return redirect()->route('layanan.index')->with('Sukses', 'Berhasil Edit Laanan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $layanan = Layanan::find($id);
        $namagambarlayanan = $layanan->gambar_layanan;
        $gambar_layanan =public_path ('file/layanan/').$namagambarlayanan;
        if(file_exists($gambar_layanan)){
            @unlink($gambar_layanan);
        }
        $layanan->delete();
        return redirect()->back()->with('Sukses', 'Berhasil Hapus Layanan');
    }
}
