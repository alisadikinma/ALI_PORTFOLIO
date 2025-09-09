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
                    <form action="{{ route('award.update', $award->id_award) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-2">
                            <label for="">Nama award <abbr title="" style="color: black">*</abbr></label>
                            <input required type="text" class="form-control" placeholder="Masukkan Judul award disini...." name="nama_award" value="{{ $award->nama_award }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan_award" id="editor" cols="30" rows="10" class="form-control">{{ $award->keterangan_award }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Gambar</label>
                            <input type="file" class="form-control" name="gambar_award" placeholder="" accept="image/*" id="preview_gambar" />
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Preview Foto</label>
                            <img src="{{ asset('file/award/'.$award->gambar_award) }}" alt="" style="width: 200px;" id="gambar_nodin">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="sequence">Sequence <abbr title="Urutan tampil" style="color: black">*</abbr></label>
                                    <input type="number" class="form-control" placeholder="Urutan tampil (contoh: 1, 2, 3...)" name="sequence" value="{{ $award->sequence ?? 0 }}" min="0">
                                    <small class="text-muted">Urutan tampil di website (angka kecil tampil duluan)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="status">Status <abbr title="" style="color: black">*</abbr></label>
                                    <select name="status" class="form-control">
                                        <option value="Active" {{ ($award->status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ ($award->status ?? 'Active') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <small class="text-muted">Hanya status Active yang tampil di website</small>
                                </div>
                            </div>
                        </div>      
                </div>
                <div class="card-footer">
                    <a href="{{ route('award.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                    <button type="submit" class="btn btn-dark"><i class="fas fa-save"></i> Save</button>
                </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ),{
            ckfinder: {
                uploadUrl: '{{route('image.upload').'?_token='.csrf_token()}}',
    }
        })
        .catch( error => {
            console.error( error );
        } );
  </script>
  <script>
      CKEDITOR.replace( 'editor', {
          filebrowserUploadUrl: "{{route('image.upload', ['_token' => csrf_token() ])}}",
          filebrowserUploadMethod: 'form'
      });
  </script>
@endsection