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
                    <form action="{{ route('layanan.update', $layanan->id_layanan) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-2">
                            <label for="">Nama Layanan <abbr title="" style="color: black">*</abbr></label>
                            <input required type="text" class="form-control" placeholder="Masukkan Judul layanan disini...." name="nama_layanan" value="{{ $layanan->nama_layanan }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Sub Nama Layanan</label>
                            <input type="text" class="form-control" placeholder="Masukkan Sub Judul layanan disini...." name="sub_nama_layanan" value="{{ $layanan->sub_nama_layanan }}">
                            <small class="text-muted">Sub judul atau deskripsi singkat layanan</small>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Icon Layanan</label>
                            <input type="file" class="form-control" name="icon_layanan" placeholder="" accept="image/*" id="preview_icon" />
                            <small class="text-muted">Format: PNG/SVG, Ukuran: < 100KB, Resolusi: 64x64 piksel (opsional)</small>
                        </div>
                        @if($layanan->icon_layanan)
                        <div class="form-group mb-2">
                            <label for="">Preview Icon</label>
                            <img src="{{ asset('file/layanan/icons/'.$layanan->icon_layanan) }}" alt="" style="width: 64px; height: 64px;" id="icon_preview">
                        </div>
                        @endif
                        <div class="form-group mb-2">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan_layanan" id="editor" cols="30" rows="10" class="form-control">{{ $layanan->keterangan_layanan }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Gambar <small class="text-muted">(Rekomendasi: 1080x608 piksel)</small></label>
                            <input type="file" class="form-control" name="gambar_layanan" placeholder="" accept="image/*" id="preview_gambar" />
                            <small class="text-muted">Format: JPG/PNG, Ukuran: < 500KB, Resolusi: 1080x608 piksel</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Preview Foto</label>
                            <img src="{{ asset('file/layanan/'.$layanan->gambar_layanan) }}" alt="" style="width: 300px; height: auto;" id="gambar_nodin">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="sequence">Sequence <abbr title="Urutan tampil" style="color: black">*</abbr></label>
                                    <input type="number" class="form-control" placeholder="Urutan tampil (contoh: 1, 2, 3...)" name="sequence" value="{{ $layanan->sequence ?? 0 }}" min="0">
                                    <small class="text-muted">Urutan tampil di website (angka kecil tampil duluan)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="status">Status <abbr title="" style="color: black">*</abbr></label>
                                    <select name="status" class="form-control">
                                        <option value="Active" {{ ($layanan->status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ ($layanan->status ?? 'Active') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <small class="text-muted">Hanya status Active yang tampil di website</small>
                                </div>
                            </div>
                        </div>      
                </div>
                <div class="card-footer">
                    <a href="{{ route('layanan.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                    <button type="submit" class="btn btn-dark"><i class="fas fa-save"></i> Save</button>
                </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            ckfinder: {
                uploadUrl: '{{route('image.upload').'?_token='.csrf_token()}}',
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection