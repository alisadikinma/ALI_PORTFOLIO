<!DOCTYPE html>
<html>
<head>
    <title>Debug - Project Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Debug - Project Categories</h1>
        <hr>
        
        <div class="row">
            <div class="col-md-6">
                <h3>Database Query Results</h3>
                <div class="alert alert-info">
                    <strong>Categories Found:</strong> {{ isset($projectCategories) ? $projectCategories->count() : 'N/A' }}
                </div>
                
                @if(isset($projectCategories) && $projectCategories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Active</th>
                                    <th>Sort Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projectCategories as $category)
                                <tr>
                                    <td>{{ $category->id ?? 'N/A' }}</td>
                                    <td>{{ $category->lookup_name ?? 'N/A' }}</td>
                                    <td>{{ $category->lookup_code ?? 'N/A' }}</td>
                                    <td>{{ $category->lookup_type ?? 'N/A' }}</td>
                                    <td>{{ $category->is_active ?? 'N/A' }}</td>
                                    <td>{{ $category->sort_order ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        No categories found or variable not set properly.
                    </div>
                @endif
            </div>
            
            <div class="col-md-6">
                <h3>Test Select Dropdown</h3>
                <form>
                    <div class="form-group mb-3">
                        <label for="project_category">Kategori Project</label>
                        <select name="project_category" id="project_category" class="form-control">
                            <option value="">Pilih Kategori Project</option>
                            @if(isset($projectCategories) && $projectCategories->count() > 0)
                                @foreach($projectCategories as $category)
                                    <option value="{{ $category->lookup_name }}">
                                        {{ $category->lookup_name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="No Data">No categories available</option>
                            @endif
                        </select>
                    </div>
                </form>
                
                <h4>Raw Data Dump:</h4>
                <pre class="bg-light p-3">{{ isset($projectCategories) ? json_encode($projectCategories, JSON_PRETTY_PRINT) : 'No data' }}</pre>
            </div>
        </div>
        
        <hr>
        <div class="mt-4">
            <a href="{{ route('project.create') }}" class="btn btn-primary">Test Create Project Form</a>
            <a href="{{ route('project.index') }}" class="btn btn-secondary">Back to Projects</a>
        </div>
    </div>
</body>
</html>
