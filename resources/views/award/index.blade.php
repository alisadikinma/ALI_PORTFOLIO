@extends('layouts.index')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
                <a href="{{ route('award.create') }}" class="btn btn-dark btn-sm" style="float: right;"><i class="fas fa-plus">Tambah</i></a>
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
                            <th scope="col">Award</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Sequence</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($award as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->nama_award }}</td>
                            <td>{!! Str::limit($row->keterangan_award, 200, '...') !!}</td>
                            <td><img src="{{ asset('file/award/'.$row->gambar_award) }}" alt="{{ $row->nama_award }}" style="width: 50px; height: 50px;"></td>
                            <td><span class="badge badge-info" style="color: black;">{{ $row->sequence ?? 0 }}</span></td>
                            <td>
                                @if(($row->status ?? 'Active') == 'Active')
                                    <span class="badge badge-success" style="color: black;">{{ $row->status }}</span>
                                @else
                                    <span class="badge badge-secondary" style="color: black;">{{ $row->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('award.edit', $row->id_award) }}" class="btn btn-primary btn-xs" style="display: inline-block"><i class="fas fa-edit">Edit</i></a>
                                <form action="{{ route('award.destroy', $row->id_award) }}" method="POST" style="display: inline-block">
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