@extends('layouts.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $title }}</h3>
                    <a href="{{ route('setting.sections') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-toggle-on"></i> Manage Sections
                    </a>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('Sukses'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form class="row g-3" method="POST" action="{{ route('setting.update', $setting->id_setting) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Nama Aplikasi</label>
                            <input type="text" name="instansi_setting" class="form-control" value="{{ $setting->instansi_setting ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Owner</label>
                            <input type="text" name="pimpinan_setting" class="form-control" value="{{ $setting->pimpinan_setting ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Meta Keyword</label>
                            <input type="text" name="keyword_setting" class="form-control" value="{{ $setting->keyword_setting ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Alamat</label>
                            <input type="text" name="alamat_setting" class="form-control" value="{{ $setting->alamat_setting ?? '' }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Tentang Saya</label>
                            <textarea name="tentang_setting" class="form-control" id="editor" cols="30" rows="5">{{ $setting->tentang_setting ?? '' }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Misi Saya</label>
                            <textarea name="misi_setting" class="form-control" id="editor1" cols="30" rows="5">{{ $setting->misi_setting ?? '' }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Visi Saya</label>
                            <textarea name="visi_setting" class="form-control" id="editor2" cols="30" rows="10">{{ $setting->visi_setting ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Youtube</label>
                            <input type="text" class="form-control" name="youtube_setting" placeholder="Masukkan Channel Youtube disini" value="{{ $setting->youtube_setting ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Instagram</label>
                            <input type="text" name="instagram_setting" class="form-control" placeholder="Masukkan akun instagram disini..." value="{{ $setting->instagram_setting ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Email</label>
                            <input type="email" class="form-control" name="email_setting" placeholder="Masukkan email disini" value="{{ $setting->email_setting ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Tik - Tok</label>
                            <input type="text" class="form-control" name="tiktok_setting" placeholder="Masukkan tik-tok disini" value="{{ $setting->tiktok_setting ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Facebook</label>
                            <input type="text" class="form-control" name="facebook_setting" placeholder="Masukkan facebook disini" value="{{ $setting->facebook_setting ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">LinkedIn</label>
                            <input type="text" class="form-control" name="linkedin_setting" placeholder="Masukkan linkedin disini" value="{{ $setting->linkedin_setting ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">No. HP</label>
                            <input type="text" name="no_hp_setting" class="form-control" placeholder="Masukkan No HP disini..." value="{{ $setting->no_hp_setting ?? '' }}">
                        </div>
                        <!-- Profile Fields -->
                        <div class="col-6 mb-3">
                            <label for="" class="form-label">Profile Title</label>
                            <input type="text" name="profile_title" class="form-control" value="{{ $setting->profile_title ?? '' }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="" class="form-label">Profile Content</label>
                            <textarea name="profile_content" class="form-control" id="editor3" rows="5">{{ $setting->profile_content ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Primary Button Title</label>
                            <input type="text" name="primary_button_title" class="form-control" value="{{ $setting->primary_button_title ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Primary Button Link</label>
                            <input type="text" name="primary_button_link" class="form-control" value="{{ $setting->primary_button_link ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Secondary Button Title</label>
                            <input type="text" name="secondary_button_title" class="form-control" value="{{ $setting->secondary_button_title ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Secondary Button Link</label>
                            <input type="text" name="secondary_button_link" class="form-control" value="{{ $setting->secondary_button_link ?? '' }}">
                        </div>
                        <!-- New Fields -->
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Years of Experience</label>
                            <input type="text" name="years_experience" class="form-control" value="{{ $setting->years_experience ?? '' }}" placeholder="Enter years of experience">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Followers Count</label>
                            <input type="text" name="followers_count" class="form-control" value="{{ $setting->followers_count ?? '' }}" placeholder="Enter followers count">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Projects Delivered</label>
                            <input type="text" name="project_delivered" class="form-control" value="{{ $setting->project_delivered ?? '' }}" placeholder="Enter number of projects">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Cost Savings</label>
                            <input type="text" step="0.01" name="cost_savings" class="form-control" value="{{ $setting->cost_savings ?? '' }}" placeholder="Enter cost savings">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Success Rate</label>
                            <input type="text" step="0.01" name="success_rate" class="form-control" value="{{ $setting->success_rate ?? '' }}" placeholder="Enter success rate">
                        </div>
                        
                        <!-- About Section Fields -->
                        <div class="col-12">
                            <h5 class="fw-bold text-primary mt-4 mb-3">About Section Settings</h5>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">About Section Title</label>
                            <input type="text" name="about_section_title" class="form-control" value="{{ $setting->about_section_title ?? '' }}" placeholder="Enter about section title">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">About Section Subtitle</label>
                            <input type="text" name="about_section_subtitle" class="form-control" value="{{ $setting->about_section_subtitle ?? '' }}" placeholder="Enter about section subtitle">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="" class="form-label">About Section Description</label>
                            <textarea name="about_section_description" class="form-control" id="editor4" rows="5">{{ $setting->about_section_description ?? '' }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">About Section Image</label>
                            <input type="file" class="form-control" name="about_section_image" placeholder="" accept="image/*" id="preview_about_image" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Preview About Image</label>
                            <img src="{{ asset('images/about/' . ($setting->about_section_image ?? '')) }}" alt="" style="width: 200px;" id="about_image_preview">
                        </div>
                        
                        <!-- Award Section Fields -->
                        <div class="col-12">
                            <h5 class="fw-bold text-primary mt-4 mb-3">Award Section Settings</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Award Section Title</label>
                            <input type="text" name="award_section_title" class="form-control" value="{{ $setting->award_section_title ?? '' }}" placeholder="Enter award section title">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Award Section Subtitle</label>
                            <input type="text" name="award_section_subtitle" class="form-control" value="{{ $setting->award_section_subtitle ?? '' }}" placeholder="Enter award section subtitle">
                        </div>
                        <!-- Existing Fields (e.g., Logo, Favicon, Maps) -->
                        <div class="col-md-12 mb-3">
                            <label for="">Logo Saya</label>
                            <input type="file" class="form-control" name="logo_setting" placeholder="" accept="image/*" id="preview_gambar" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Preview Logo</label>
                            <img src="{{ asset('logo/' . ($setting->logo_setting ?? '')) }}" alt="" style="width: 200px;" id="gambar_nodin">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Photo Saya</label>
                            <input type="file" class="form-control" name="favicon_setting" placeholder="" accept="image/*" id="preview_gambar" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Preview Photo</label>
                            <img src="{{ asset('favicon/' . ($setting->favicon_setting ?? '')) }}" alt="" style="width: 200px;" id="gambar_nodin">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Link Maps</label>
                            <textarea name="maps_setting" class="form-control" rows="3">{{ $setting->maps_setting ?? '' }}</textarea>
                        </div>
                        <iframe class="w-100 rounded" src="{{ $setting->maps_setting ?? '' }}" frameborder="0" style="min-height: 300px; border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-dark" style="float: right"> <i class="fas fa-save"></i> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor1'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor2'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor3'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor4'))
        .catch(error => {
            console.error(error);
        });
</script>

@endsection