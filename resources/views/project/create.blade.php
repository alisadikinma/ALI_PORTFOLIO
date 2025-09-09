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
                <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                   <div class="form-group mb-2">
                        <label for="nama_client">Nama Client</label>
                        <input type="text" class="form-control" id="nama_client" placeholder="Masukkan nama client disini...." name="nama_client" value="{{ old('nama_client') }}">
                    </div>
                   <div class="form-group mb-2">
                        <label for="lokasi_client">Lokasi Client</label>
                        <input type="text" class="form-control" id="lokasi_client" name="lokasi_client" placeholder="Masukkan alamat client disini...." value="{{ old('lokasi_client') }}">
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Nama Project <abbr title="" style="color: black">*</abbr></label>
                        <input type="text" class="form-control" placeholder="Masukkan Judul Project disini...." name="nama_project" value="{{ old('nama_project') }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Jenis Project <abbr title="" style="color: black">*</abbr></label>
                        <select name="jenis_project" class="form-control">
                            <option value="Media Sosial">Media Sosial</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Jual Beli atau Ecommerce">Jual Beli atau Ecommerce</option>
                            <option value="Pencarian">Pencarian</option>
                            <option value="Informasi dan Berita">Informasi dan Berita</option>
                            <option value="Pemesanan">Pemesanan</option>
                            <option value="Event">Event</option>
                            <option value="Game">Game</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Status</label>
                        <select name="info_project" id="dropdown" class="form-control">
                            <option></option>
                            <option>Selesai</option>
                            <option>Proses</option>
                            <option>Perencanaan</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Keterangan</label>
                        <textarea name="keterangan_project" id="editor" cols="30" rows="10" class="form-control">{{ old('keterangan_project') }}</textarea>
                    </div>
                     <div class="form-group mb-2">
                        <label for="">Url Project <abbr title="" style="color: black">*</abbr></label>
                        <input type="text" class="form-control" placeholder="Masukkan Url Project disini...." name="url_project" value="{{ old('url_project') }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Gambar <abbr title="" style="color: black">*</abbr> </label>
                        <input id="inputImg" type="file" accept="image/*" name="gambar_project" class="form-control">
                        <img class="d-none w-25 h-25 my-2" id="previewImg" src="#" alt="Preview image">
                    </div>

                     <div class="form-group mb-2">
                        <label for="">Gambar1 <abbr title="" style="color: black">*</abbr> </label>
                        <input id="inputImg" type="file" accept="image/*" name="gambar_project1" class="form-control">
                        <img class="d-none w-25 h-25 my-2" id="previewImg" src="#" alt="Preview image">
                    </div>

                     <div class="form-group mb-2">
                        <label for="">Gambar 2 <abbr title="" style="color: black">*</abbr> </label>
                        <input id="inputImg" type="file" accept="image/*" name="gambar_project2" class="form-control">
                        <img class="d-none w-25 h-25 my-2" id="previewImg" src="#" alt="Preview image">
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
