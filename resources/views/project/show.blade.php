@extends('layouts.index')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
                <div class="card-tools">
                    <a href="{{ route('project.edit', $project->id_project) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('project.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Nama Project</th>
                                <td>{{ $project->project_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Client</th>
                                <td>{{ $project->client_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>{{ $project->location ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Kategori Project</th>
                                <td>
                                    <span class="badge badge-info">{{ $project->project_category ?? 'N/A' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-{{ $project->status == 'Active' ? 'success' : 'secondary' }}">
                                        {{ $project->status ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            @if($project->url_project)
                            <tr>
                                <th>URL Project</th>
                                <td>
                                    <a href="{{ $project->url_project }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Lihat Project
                                    </a>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>Slug</th>
                                <td><code>{{ $project->slug_project ?? 'N/A' }}</code></td>
                            </tr>
                            <tr>
                                <th>Urutan</th>
                                <td>{{ $project->sequence ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat</th>
                                <td>{{ $project->created_at ? \Carbon\Carbon::parse($project->created_at)->format('d M Y H:i') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Diupdate</th>
                                <td>{{ $project->updated_at ? \Carbon\Carbon::parse($project->updated_at)->format('d M Y H:i') : 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Gambar Project</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $images = $project->images ? json_decode($project->images, true) : [];
                                @endphp
                                
                                @if(!empty($images))
                                    <div class="row">
                                        @foreach($images as $image)
                                        <div class="col-12 mb-3">
                                            <div class="card">
                                                <img src="{{ asset('images/projects/' . $image) }}" 
                                                     class="card-img-top" 
                                                     style="height: 150px; object-fit: cover;" 
                                                     alt="Project Image">
                                                <div class="card-body p-2">
                                                    @if($project->featured_image == $image)
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-star"></i> Featured
                                                        </span>
                                                    @endif
                                                    <small class="text-muted d-block">{{ $image }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-2x mb-2"></i>
                                        <p>No images uploaded</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                @if($project->description)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Deskripsi Project</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-justify">
                                    {{ $project->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Detailed Info Section -->
                @if($project->info_project)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Detail Project</h5>
                            </div>
                            <div class="card-body">
                                <div class="content">
                                    {!! $project->info_project !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="btn-group" role="group">
                            <a href="{{ route('project.edit', $project->id_project) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Project
                            </a>
                            @if($project->slug_project)
                            <a href="{{ route('project.public.show', $project->slug_project) }}" target="_blank" class="btn btn-info">
                                <i class="fas fa-eye"></i> Preview Public
                            </a>
                            @endif
                            <form action="{{ route('project.destroy', $project->id_project) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger show_confirm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.querySelector('.show_confirm');
    if (deleteButton) {
        deleteButton.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest("form");
            
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
});
</script>

<style>
.content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.content table {
    margin-top: 1rem;
    margin-bottom: 1rem;
}

.content blockquote {
    padding: 0.5rem 1rem;
    margin: 0.8rem 0;
    border-left: 0.25rem solid #FFD700;
    background-color: #f8f9fa;
}
</style>
@endsection
