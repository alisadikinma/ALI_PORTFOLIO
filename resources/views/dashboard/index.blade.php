@extends('layouts.index')
@section('content')

<div class="row">

    {{-- Card Statistik --}}
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm border-0 rounded-lg text-white bg-primary">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">Portfolio</h6>
                    <h3 class="mb-0">{{ $countProject }}</h3>
                </div>
                <div>
                    <i class="fas fa-briefcase fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm border-0 rounded-lg text-white bg-success">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">Galeri</h6>
                    <h3 class="mb-0">{{ $countGaleri }}</h3>
                </div>
                <div>
                    <i class="fas fa-image fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm border-0 rounded-lg text-white bg-warning">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">Artikel</h6>
                    <h3 class="mb-0">{{ $countBerita }}</h3>
                </div>
                <div>
                    <i class="fas fa-newspaper fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm border-0 rounded-lg text-white bg-danger">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">Pesan</h6>
                    <h3 class="mb-0">{{ $countPesan }}</h3>
                </div>
                <div>
                    <i class="fas fa-envelope fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Tabel Data Pesan --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-light">
                <h5 class="mb-0">Pesan Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Layanan</th>
                                <th>Budget</th>
                                <th>Pesan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contacts as $key => $contact)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $contact->full_name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->subject }}</td>
                                    <td>{{ $contact->service }}</td>
                                    <td>{{ $contact->budget }}</td>
                                    <td>{{ Str::limit($contact->message, 50) }}</td>
                                    <td>{{ $contact->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada pesan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
