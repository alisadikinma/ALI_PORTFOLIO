@extends('layouts.index')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
                <a href="{{ route('berita.create') }}" class="btn btn-dark btn-sm" style="float: right;">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div class="card-body table table-responsive">
                @if ($message = Session::get('Sukses'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <table class="table table-bordered" id="example2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Isi</th>
                            <th>Thumbnail</th>
                            <th>Related Berita</th> {{-- kolom baru --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($berita as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_berita)->isoFormat('dddd, D MMMM Y') }}</td>
                            <td>{{ $row->judul_berita }}</td>
                            <td>{{ $row->kategori_berita }}</td>
                            <td>{!! Str::limit($row->isi_berita, 100, '...') !!}</td>
                            <td>
                                <img src="{{ asset('file/berita/'.$row->gambar_berita) }}"
                                    alt="{{ $row->judul_berita }}"
                                    style="width: 100px; height: 100px;">
                            </td>
                            <td>
                                @php
                                $related = $row->relatedBerita();
                                @endphp

                                @if ($related->count() > 0)
                                <ul class="mb-0">
                                    @foreach ($related as $item)
                                    <li>
                                        <a href="{{ route('berita.show', $item->slug_berita) }}">
                                            {{ $item->judul_berita }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('berita.show', $row->slug_berita) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Show
                                </a>
                                <a href="{{ route('berita.edit', $row->id_berita) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('berita.destroy', $row->id_berita) }}" method="POST" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-flat show_confirm">
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
        let deleteForms = document.querySelectorAll('.show_confirm');
        deleteForms.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
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
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection