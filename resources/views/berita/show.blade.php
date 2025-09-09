@extends('layouts.index')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ $berita->judul_berita }}</h3>
                <a href="{{ route('berita.index') }}" class="btn btn-secondary btn-sm" style="float:right;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <p><strong>Tanggal:</strong> {{ Carbon\Carbon::parse($berita->tanggal_berita)->isoFormat('dddd, D MMMM Y') }}</p>
                <p><strong>Kategori:</strong> {{ $berita->kategori_berita }}</p>
                <p><strong>Isi:</strong></p>
                <div>{!! $berita->isi_berita !!}</div>

                @if ($berita->gambar_berita)
                <div class="mt-3">
                    <img src="{{ asset('file/berita/'.$berita->gambar_berita) }}" alt="{{ $berita->judul_berita }}" style="max-width: 400px;">
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
