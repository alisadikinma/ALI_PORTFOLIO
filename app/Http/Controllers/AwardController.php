<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Award & Recognition';
        
        // Use DB table with get() to return proper objects
        $award = DB::table('award')
            ->orderBy('sequence', 'asc')
            ->orderByDesc('id_award')
            ->get();
        
        return view('award.index', compact('award', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Award & Recognition';
        return view('award.create', compact('title'));
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
            'nama_award' => 'required',
            'gambar_award' => 'required',
            'keterangan_award' => 'required',
        ],$messages);
        $gambar_award = $request->file('gambar_award');
        $namagambaraward = 'award'.date('Ymdhis').'.'.$request->file('gambar_award')->getClientOriginalExtension();
        $gambar_award->move('file/award/',$namagambaraward);

        $data = new Award();
        $data->nama_award = $request->nama_award;
        $data->gambar_award = $namagambaraward;
        $data->keterangan_award = $request->keterangan_award;
        $data->slug_award = Str::slug($request->nama_award);
        $data->sequence = $request->sequence ?? 0;
        $data->status = $request->status ?? 'Active';
        $data->save();
        return redirect()->route('award.index')->with('Sukses', 'Berhasil Tambah Award');
    }

    /**
     * Display the specified resource.
     */
    public function show(Award $award)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $award = Award::find($id);
        $title = 'Edit Award & Recognition';
        return view('award.edit', compact('award', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $award = Award::find($id);
        $namagambaraward = $award->gambar_award;
        $update = [
            'nama_award' => $request->nama_award,
            'gambar_award' => $namagambaraward,
            'keterangan_award' => $request->keterangan_award,
            'slug_award' => Str::slug($request->nama_award),
            'sequence' => $request->sequence ?? 0,
            'status' => $request->status ?? 'Active',
        ];
        if ($request->gambar_award != ""){
            $request->gambar_award->move(public_path('file/award/'), $namagambaraward);
        }   
        $award->update($update);
        return redirect()->route('award.index')->with('Sukses', 'Berhasil Edit Laanan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $award = Award::find($id);
        $namagambaraward = $award->gambar_award;
        $gambar_award =public_path ('file/award/').$namagambaraward;
        if(file_exists($gambar_award)){
            @unlink($gambar_award);
        }
        $award->delete();
        return redirect()->back()->with('Sukses', 'Berhasil Hapus Award');
    }
}
