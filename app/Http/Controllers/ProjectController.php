<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Portfolio';
        $project = DB::table('project')->orderByDesc('id_project')->get();
        return view('project.index', compact('project', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Portfolio';
        return view('project.create', compact('title'));
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
            'nama_project' => 'required',
            'gambar_project' => 'required',
            'gambar_project1' => 'required',
            'gambar_project2' => 'required',
            'keterangan_project' => 'required',
            'info_project' => 'required',
            'jenis_project' => 'required',
            'nama_client' => 'required', // Tambahkan validasi ini
            'lokasi_client' => 'required', // Tambahkan validasi ini
        ], $messages);

        $gambar_project = $request->file('gambar_project');
        $namagambarproject = 'Project' . date('Ymdhis') . '.' . $request->file('gambar_project')->getClientOriginalExtension();
        $gambar_project->move('file/project/', $namagambarproject);

        $gambar_project1 = $request->file('gambar_project1');
        $namagambarproject1 = 'Project1' . date('Ymdhis') . '.' . $request->file('gambar_project1')->getClientOriginalExtension();
        $gambar_project1->move('file/project1/', $namagambarproject1);

        $gambar_project2 = $request->file('gambar_project2');
        $namagambarproject2 = 'Project2' . date('Ymdhis') . '.' . $request->file('gambar_project2')->getClientOriginalExtension();
        $gambar_project2->move('file/project2/', $namagambarproject2);

        $data = new Project();
        $data->nama_project = $request->nama_project;
        $data->gambar_project = $namagambarproject;
        $data->gambar_project1 = $namagambarproject1;
        $data->gambar_project2 = $namagambarproject2;
        $data->keterangan_project = $request->keterangan_project;
        $data->info_project = $request->info_project;
        $data->url_project = $request->url_project;
        $data->slug_project = Str::slug($request->nama_project);
        $data->jenis_project = $request->jenis_project;
        $data->nama_client = $request->nama_client; // Tambahkan ini
        $data->lokasi_client = $request->lokasi_client; // Tambahkan ini
        $data->save();

        return redirect()->route('project.index')->with('Sukses', 'Berhasil Tambah Project');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        $title = 'Detail Portfolio';
        return view('project.show', compact('project', 'title'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $project = Project::find($id);
        $title = 'Edit Portfolio';
        return view('project.edit', compact('project', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        // Menyimpan nama gambar lama sebelum diupdate
        $namagambarproject = $project->gambar_project;
        $namagambarproject1 = $project->gambar_project1;
        $namagambarproject2 = $project->gambar_project2;

        $update = [
            'nama_project' => $request->nama_project,
            'keterangan_project' => $request->keterangan_project,
            'info_project' => $request->info_project,
            'url_project' => $request->url_project,
            'slug_project' => Str::slug($request->nama_project),
            'jenis_project' => $request->jenis_project,
            'nama_client' => $request->nama_client,
            'lokasi_client' => $request->lokasi_client,
        ];

        // Cek apakah gambar baru di-upload untuk gambar_project
        if ($request->hasFile('gambar_project')) {
            // Hapus gambar lama
            if (file_exists(public_path('file/project/' . $namagambarproject))) {
                unlink(public_path('file/project/' . $namagambarproject));
            }

            // Upload gambar baru
            $gambar_project = $request->file('gambar_project');
            $namagambarproject = 'Project' . date('Ymdhis') . '.' . $gambar_project->getClientOriginalExtension();
            $gambar_project->move('file/project/', $namagambarproject);

            // Update nama gambar pada data
            $update['gambar_project'] = $namagambarproject;
        }

        // Cek apakah gambar baru di-upload untuk gambar_project1
        if ($request->hasFile('gambar_project1')) {
            // Hapus gambar lama
            if (!empty($namagambarproject1) && file_exists(public_path('file/project1/' . $namagambarproject1))) {
                unlink(public_path('file/project1/' . $namagambarproject1));
            }

            // Upload gambar baru
            $gambar_project1 = $request->file('gambar_project1');
            $namagambarproject1 = 'Project1' . date('Ymdhis') . '.' . $gambar_project1->getClientOriginalExtension();
            $gambar_project1->move('file/project1/', $namagambarproject1);

            // Update nama gambar pada data
            $update['gambar_project1'] = $namagambarproject1;
        }

        // Cek apakah gambar baru di-upload untuk gambar_project2
        if ($request->hasFile('gambar_project2')) {
            // Hapus gambar lama
            if (!empty($namagambarproject2) && file_exists(public_path('file/project2/' . $namagambarproject2))) {
                unlink(public_path('file/project2/' . $namagambarproject2));
            }

            // Upload gambar baru
            $gambar_project2 = $request->file('gambar_project2');
            $namagambarproject2 = 'Project2' . date('Ymdhis') . '.' . $gambar_project2->getClientOriginalExtension();
            $gambar_project2->move('file/project2/', $namagambarproject2);

            // Update nama gambar pada data
            $update['gambar_project2'] = $namagambarproject2;
        }

        // Update data project
        $project->update($update);

        return redirect()->route('project.index')->with('Sukses', 'Berhasil Edit Project');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        $namagambarproject = $project->gambar_project;
        $gambar_project = public_path('file/team/') . $namagambarproject;
        if (file_exists($gambar_project)) {
            @unlink($gambar_project);
        }
        $project->delete();
        return redirect()->back()->with('Sukses', 'Berhasil Hapus Project');
    }
}
