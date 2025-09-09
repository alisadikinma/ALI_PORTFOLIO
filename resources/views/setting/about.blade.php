@extends('layouts.index')

@section('title', $title)

@section('content')
<div class="container py-5">

    <div class="text-center mb-5">
        @if($setting->logo_setting)
        <img src="{{ asset('logo/' . $setting->logo_setting) }}" alt="Logo" style="height: 200px;">
        @endif
        <h2 class="fw-bold">{{ $setting->instansi_setting }}</h2>
        <p class="text-muted mb-0">Owner: <strong>{{ $setting->pimpinan_setting }}</strong></p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Tentang Sistem</h4>
                    <p class="mb-0">{!! $setting->tentang_setting !!}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Visi</h4>
                    <p class="mb-0">{!! $setting->visi_setting !!}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Misi</h4>
                    <p class="mb-0">{!! $setting->misi_setting !!}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Kontak</h4>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Alamat:</strong> {{ $setting->alamat_setting }}</li>
                        <li><strong>Email:</strong> <a href="mailto:{{ $setting->email_setting }}">{{ $setting->email_setting }}</a></li>
                        <li><strong>No HP:</strong> <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->no_hp_setting) }}" target="_blank">{{ $setting->no_hp_setting }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Sosial Media</h4>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <strong>Instagram:</strong>
                            <a href="https://instagram.com/{{ $setting->instagram_setting }}" target="_blank">{{ $setting->instagram_setting }}</a>
                        </li>
                        <li>
                            <strong>YouTube:</strong>
                            <a href="{{ $setting->youtube_setting }}" target="_blank">{{ $setting->youtube_setting }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <iframe class="w-100 rounded-bottom" src="{{ $setting->maps_setting }}" frameborder="0" style="min-height: 300px; border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
    </div>

</div>
@endsection
