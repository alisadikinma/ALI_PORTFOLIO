@extends('layouts.index')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('project.update', $project->id_project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-2">
                        <label for="">Nama Client <abbr title="" style="color: black">*</abbr></label>
                        <input required type="text" class="form-control" name="nama_client" value="{{ $project->nama_client }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="">lokasi Client <abbr title="" style="color: black">*</abbr></label>
                        <input required type="text" class="form-control" name="lokasi_client" value="{{ $project->lokasi_client }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Nama Project <abbr title="" style="color: black">*</abbr></label>
                        <input required type="text" class="form-control" placeholder="Masukkan Judul Project disini...." name="nama_project" value="{{ $project->nama_project }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Jenis Project <abbr title="" style="color: black">*</abbr></label>
                        <select name="jenis_project" class="form-control">
                            <option value="Media Sosial" {{ $project->jenis_project == 'Media Sosial' ? 'selected' : '' }}>Media Sosial</option>
                            <option value="Sistem Informasi" {{ $project->jenis_project == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                            <option value="Jual Beli atau Ecommerce" {{ $project->jenis_project == 'Jual Beli atau Ecommerce' ? 'selected' : '' }}>Jual Beli atau Ecommerce</option>
                            <option value="Pencarian" {{ $project->jenis_project == 'Pencarian' ? 'selected' : '' }}>Pencarian</option>
                            <option value="Informasi dan Berita" {{ $project->jenis_project == 'Informasi dan Berita' ? 'selected' : '' }}>Informasi dan Berita</option>
                            <option value="Pemesanan" {{ $project->jenis_project == 'Pemesanan' ? 'selected' : '' }}>Pemesanan</option>
                            <option value="Event" {{ $project->jenis_project == 'Event' ? 'selected' : '' }}>Event</option>
                            <option value="Game" {{ $project->jenis_project == 'Game' ? 'selected' : '' }}>Game</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Info Project *(Maks 9 Kata)</label>
                        <input required type="text" class="form-control" placeholder="Masukkan Info Project disini...." name="info_project" value="{{ $project->info_project }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Keterangan</label>
                        <textarea name="keterangan_project" id="editor" cols="30" rows="10" class="form-control">{{ $project->keterangan_project }}</textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">URL</label>
                        <input required type="text" class="form-control" placeholder="Masukkan Url Project disini...." name="url_project" value="{{ $project->url_project }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Gambar</label>
                        <input type="file" class="form-control" name="gambar_project" placeholder="" accept="image/*" id="preview_gambar" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Preview Foto</label>
                        <img src="{{ asset('file/project/'.$project->gambar_project) }}" alt="" style="width: 200px;" id="gambar_nodin">
                    </div>

                     <div class="form-group mb-3">
                        <label for="">Gambar 1</label>
                        <input type="file" class="form-control" name="gambar_project1" placeholder="" accept="image/*" id="preview_gambar" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Preview Foto 1</label>
                        <img src="{{ asset('file/project1/'.$project->gambar_project1) }}" alt="" style="width: 200px;" id="gambar_nodin">
                    </div>

                     <div class="form-group mb-3">
                        <label for="">Gambar 2</label>
                        <input type="file" class="form-control" name="gambar_project2" placeholder="" accept="image/*" id="preview_gambar" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Preview Foto 2</label>
                        <img src="{{ asset('file/project2/'.$project->gambar_project2) }}" alt="" style="width: 200px;" id="gambar_nodin">
                    </div>

            </div>
            <div class="card-footer">
                <a href="{{ route('project.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                <button type="submit" class="btn btn-dark"><i class="fas fa-save"></i> Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.getElementById('inputImg').addEventListener('change', function() {
        // Get the file input value and create a URL for the selected image
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').setAttribute('src', e.target.result);
                document.getElementById('previewImg').classList.add("d-block");
            };
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>

<script>
   ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
