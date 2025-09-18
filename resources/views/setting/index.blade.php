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
                    
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-4" id="settingTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main" type="button" role="tab" aria-controls="main" aria-selected="true">
                                <i class="fas fa-cog"></i> Main Settings
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="false">
                                <i class="fas fa-user"></i> About Settings
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                                <i class="fas fa-phone"></i> Contact Settings
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="award-tab" data-bs-toggle="tab" data-bs-target="#award" type="button" role="tab" aria-controls="award" aria-selected="false">
                                <i class="fas fa-trophy"></i> Award Settings
                            </button>
                        </li>
                    </ul>

                    <form class="row g-3" method="POST" action="{{ route('setting.update', $setting->id_setting) }}" enctype="multipart/form-data" id="settingForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Tab Content -->
                        <div class="tab-content" id="settingTabContent">
                            <!-- Main Tab -->
                            <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                                <div class="row">
                                    <!-- Basic Information -->
                                    <div class="col-12">
                                        <h5 class="fw-bold text-primary mb-3">Basic Information</h5>
                                    </div>
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




                                    <!-- Media -->
                                    <div class="col-12">
                                        <h5 class="fw-bold text-primary mt-4 mb-3">Media</h5>
                                    </div>
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
                                        <input type="file" class="form-control" name="favicon_setting" placeholder="" accept="image/*" id="preview_gambar2" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Preview Photo</label>
                                        <img src="{{ asset('favicon/' . ($setting->favicon_setting ?? '')) }}" alt="" style="width: 200px;" id="gambar_nodin2">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="">Link Maps</label>
                                        <textarea name="maps_setting" class="form-control" rows="3">{{ $setting->maps_setting ?? '' }}</textarea>
                                    </div>
                                    <iframe class="w-100 rounded" src="{{ $setting->maps_setting ?? '' }}" frameborder="0" style="min-height: 300px; border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                </div>
                            </div>

                            <!-- About Tab -->
                            <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about-tab">
                                <div class="row">
                                    <!-- Profile Section -->
                                    <div class="col-12">
                                        <h5 class="fw-bold text-primary mb-3">Profile Section</h5>
                                    </div>
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

                                    <!-- Statistics -->
                                    <div class="col-12">
                                        <h5 class="fw-bold text-primary mt-4 mb-3">Statistics</h5>
                                    </div>
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

                                    <!-- About Section Settings -->
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
                                </div>
                            </div>

                            <!-- Contact Tab -->
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="row">
                                    <!-- Social Media -->
                                    <div class="col-12">
                                        <h5 class="fw-bold text-primary mb-3">Social Media</h5>
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
                                </div>
                            </div>

                            <!-- Award Tab -->
                            <div class="tab-pane fade" id="award" role="tabpanel" aria-labelledby="award-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="fw-bold text-primary mb-3">Award Section Settings</h5>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="" class="form-label">Award Section Title</label>
                                        <input type="text" name="award_section_title" class="form-control" value="{{ $setting->award_section_title ?? '' }}" placeholder="Enter award section title">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="" class="form-label">Award Section Subtitle</label>
                                        <input type="text" name="award_section_subtitle" class="form-control" value="{{ $setting->award_section_subtitle ?? '' }}" placeholder="Enter award section subtitle">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-dark" form="settingForm" style="float: right"> <i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
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