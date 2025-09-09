@extends('layouts.index')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
                <a href="{{ route('testimonial.create') }}" class="btn btn-dark btn-sm" style="float: right;"><i class="fas fa-plus">Tambah</i></a>
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
                            <th scope="col">No</th>
                            <th scope="col">Client</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Logo</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($testimonial as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->judul_testimonial }}</td>
                            <td>{!! Str::limit($row->deskripsi_testimonial, 200, '...') !!}</td>
                            <td><img src="{{ asset('file/testimonial/'.$row->gambar_testimonial) }}" alt="{{ $row->judul_testimonial }}" style="width: 50px; height: 50px;"></td>
                            <td>
                                <a href="{{ route('testimonial.edit', $row->id_testimonial) }}" class="btn btn-primary btn-xs" style="display: inline-block"><i class="fas fa-edit">Edit</i></a>
                                <form action="{{ route('testimonial.destroy', $row->id_testimonial) }}" method="POST" style="display: inline-block">
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