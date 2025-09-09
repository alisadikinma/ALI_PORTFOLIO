@extends('layouts.index')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
                <a href="{{ route('project.create') }}" class="btn btn-dark btn-sm" style="float: right;">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div class="card-body table table-responsive">
                @if ($message = Session::get('Sukses'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p>{{ $message }}</p>
                </div>
                @endif
                <table class="table table-bordered" id="example3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Project</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->nama_project }}</td>
                            <td>{{ $row->jenis_project }}</td>
                            <td>{{ $row->info_project }}</td>
                           
                            <td>
                                <img src="{{ asset('file/project/'.$row->gambar_project) }}" alt="{{ $row->nama_project }}" style="width: 50px; height: 50px; object-fit: cover;">
                                <img src="{{ asset('file/project1/'.$row->gambar_project1) }}" alt="{{ $row->nama_project }}" style="width: 50px; height: 50px; object-fit: cover;">
                                <img src="{{ asset('file/project2/'.$row->gambar_project2) }}" alt="{{ $row->nama_project }}" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <a href="{{ route('project.edit', $row->id_project) }}" class="btn btn-primary btn-xs">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('project.show', $row->id_project) }}" class="btn btn-info btn-xs">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                <form action="{{ route('project.destroy', $row->id_project) }}" method="POST" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs btn-flat show_confirm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // tangkap semua tombol dengan class show_confirm
        let deleteForms = document.querySelectorAll('.show_confirm');
        deleteForms.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // hentikan submit form dulu
                let form = this.closest("form");
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // lanjut submit jika klik "Ya"
                    }
                });
            });
        });
    });
</script>
@endsection