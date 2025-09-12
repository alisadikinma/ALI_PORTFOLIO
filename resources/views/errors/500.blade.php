<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Error - Portfolio Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0"><i class="fas fa-exclamation-triangle"></i> System Error</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-bug fa-4x text-danger mb-3"></i>
                            <h5>Oops! Something went wrong</h5>
                        </div>
                        
                        @if(isset($error))
                        <div class="alert alert-danger">
                            <strong>Error Details:</strong><br>
                            {{ $error }}
                        </div>
                        @endif
                        
                        <div class="alert alert-info">
                            <strong>What you can do:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Try clearing the cache: <a href="/clear-cache.php" class="btn btn-sm btn-outline-primary">Clear Cache</a></li>
                                <li>Check database connection: <a href="/test/database" class="btn btn-sm btn-outline-info">Test Database</a></li>
                                <li>Go back to admin panel: <a href="/project" class="btn btn-sm btn-outline-success">Admin Panel</a></li>
                            </ul>
                        </div>
                        
                        <div class="text-center">
                            <a href="/project" class="btn btn-primary">
                                <i class="fas fa-home"></i> Back to Admin Panel
                            </a>
                            <a href="/test/database" class="btn btn-info">
                                <i class="fas fa-database"></i> Test Database
                            </a>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <small>Error occurred at: {{ now()->format('Y-m-d H:i:s') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
