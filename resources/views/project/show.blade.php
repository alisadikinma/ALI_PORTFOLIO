@extends('layouts.index')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
                <a href="{{ route('project.index') }}" class="btn btn-secondary btn-sm float-right">Kembali</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama Project</th>
                        <td>{{ $project->nama_project }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Project</th>
                        <td>{{ $project->jenis_project }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $project->info_project }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{!! $project->keterangan_project !!}</td>
                    </tr>
                    <tr>
                        <th>URL Project</th>
                        <td><a href="https://{{ $project->url_project }}" target="_blank">{{ $project->url_project }}</a></td>
                    </tr>
                    <tr>
                        <th>Client</th>
                        <td>{{ $project->nama_client }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi Client</th>
                        <td>{{ $project->lokasi_client }}</td>
                    </tr>
                    <tr>
                        <th>Gambar</th>
                        <td>
                            <img src="{{ asset('file/project/' . $project->gambar_project) }}" alt="{{ $project->nama_project }}" style="width: 200px; object-fit: cover;">
                            <img src="{{ asset('file/project1/' . $project->gambar_project1) }}" alt="{{ $project->nama_project }}" style="width: 200px; object-fit: cover;">
                            <img src="{{ asset('file/project2/' . $project->gambar_project2) }}" alt="{{ $project->nama_project }}" style="width: 200px; object-fit: cover;">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
