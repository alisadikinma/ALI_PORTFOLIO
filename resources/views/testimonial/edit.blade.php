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
                    <form action="{{ route('testimonial.update', $testimonial->id_testimonial) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-2">
                            <label for="">Client <abbr title="" style="color: black">*</abbr></label>
                            <input type="text" class="form-control" placeholder="Masukkan judul disini...." name="judul_testimonial" value="{{ $testimonial->judul_testimonial }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Jabatan <abbr title="" style="color: black">*</abbr></label>
                            <input type="text" class="form-control" name="jabatan" value="{{ $testimonial->jabatan }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Deskripsi</label>
                            <textarea name="deskripsi_testimonial" id="editor" cols="30" rows="10" class="form-control">{{ $testimonial->deskripsi_testimonial }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Icon</label>
                            <input type="file" class="form-control" name="gambar_testimonial" placeholder="" accept="image/*" id="preview_gambar" />
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Preview</label>
                            <img src="{{ asset('file/testimonial/'.$testimonial->gambar_testimonial) }}" alt="" style="width: 200px;" id="gambar_nodin">
                        </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('testimonial.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                    <button type="submit" class="btn btn-dark"><i class="fas fa-save"></i> Save</button>
                </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
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
