@extends('layouts.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-4">
                <h2 class="text-dark"><i class="fas fa-user-circle me-2"></i>My Profile</h2>
            </div>
        </div>
    </div>
    
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-signature me-1"></i>Full Name
                            </label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ Auth::user()->name ?? '' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ Auth::user()->email ?? '' }}" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Change Password -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Change Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-key me-1"></i>Current Password
                            </label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>New Password
                            </label>
                            <input type="password" class="form-control" id="password" 
                                   name="password" required>
                            <small class="form-text text-muted">
                                Password must be at least 8 characters long.
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-1"></i>Confirm New Password
                            </label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" required>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync-alt me-2"></i>Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Account Information -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-id-badge me-2"></i>User ID:</strong> 
                                <span class="badge bg-secondary ms-2">{{ Auth::user()->id ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-calendar-plus me-2"></i>Account Created:</strong> 
                                <span class="ms-2">
                                    @if(Auth::user()->created_at)
                                        {{ Auth::user()->created_at->format('d M Y, H:i') }}
                                    @else
                                        <span class="text-muted">Not available</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-calendar-check me-2"></i>Last Updated:</strong> 
                                <span class="ms-2">
                                    @if(Auth::user()->updated_at)
                                        {{ Auth::user()->updated_at->format('d M Y, H:i') }}
                                    @else
                                        <span class="text-muted">Not available</span>
                                    @endif
                                </span>
                            </div>
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-envelope-check me-2"></i>Email Status:</strong> 
                                @if(Auth::user()->email_verified_at)
                                    <span class="badge bg-success ms-2">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark ms-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Not Verified
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional User Info -->
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><i class="fas fa-user-tag me-2"></i>Account Type:</strong>
                                    @if(Auth::user()->id == 1)
                                        <span class="badge bg-danger ms-2">
                                            <i class="fas fa-crown me-1"></i>Super Admin
                                        </span>
                                    @else
                                        <span class="badge bg-primary ms-2">
                                            <i class="fas fa-user me-1"></i>User
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Dark mode compatibility */
    body.dark .page-header h2,
    body.dark-mode .page-header h2 {
        color: #f1f1f1 !important;
    }
    
    body.dark .info-item strong,
    body.dark-mode .info-item strong {
        color: #f1f1f1 !important;
    }
    
    body.dark .info-item span,
    body.dark-mode .info-item span {
        color: #d1d1d1 !important;
    }
    
    /* Card styling for better appearance */
    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
        font-weight: 600;
    }
    
    /* Form improvements */
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Info item styling */
    .info-item {
        padding: 8px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    /* Dark mode info item borders */
    body.dark .info-item,
    body.dark-mode .info-item {
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }
</style>
@endsection
