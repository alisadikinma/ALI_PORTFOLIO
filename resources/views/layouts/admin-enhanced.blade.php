<?php
use Illuminate\Support\Facades\DB;
$konf = DB::table('setting')->first();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $konf->instansi_setting }} - Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('logo/' . $konf->logo_setting) }}" rel="icon">

    <!-- Gen Z Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Modern Icon Libraries -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* Gen Z Color Palette */
            --electric-purple: #8b5cf6;
            --cyber-pink: #ec4899;
            --neon-green: #10b981;
            --aurora-blue: #06b6d4;
            --neon-yellow: #fbbf24;
            --dark-surface: #0f0f23;
            --card-surface: #1a1a2e;
            --glass-bg: rgba(26, 26, 46, 0.8);

            /* Gradients */
            --gradient-cyber: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            --gradient-aurora: linear-gradient(135deg, #06b6d4 0%, #8b5cf6 50%, #ec4899 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            --gradient-warning: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);

            /* Effects */
            --glow-purple: 0 0 20px rgba(139, 92, 246, 0.4), 0 0 40px rgba(139, 92, 246, 0.2);
            --glow-pink: 0 0 20px rgba(236, 72, 153, 0.4), 0 0 40px rgba(236, 72, 153, 0.2);
            --glow-green: 0 0 20px rgba(16, 185, 129, 0.4), 0 0 40px rgba(16, 185, 129, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', system-ui, sans-serif;
            background: var(--dark-surface);
            background-image:
                radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(6, 182, 212, 0.2) 0%, transparent 50%);
            color: white;
            overflow-x: hidden;
        }

        .admin-container {
            min-height: 100vh;
            display: flex;
            position: relative;
        }

        /* Modern Sidebar */
        .modern-sidebar {
            width: 280px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: var(--gradient-cyber);
            background-size: 200% 200%;
            animation: gradient-shift 4s ease infinite;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-brand img {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .sidebar-user {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .user-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            background: var(--gradient-aurora);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            position: relative;
        }

        .user-avatar::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background: var(--neon-green);
            border-radius: 50%;
            border: 2px solid var(--dark-surface);
        }

        .user-info h6 {
            margin: 0;
            font-weight: 600;
            color: white;
        }

        .user-info small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0 1.5rem 0.75rem;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: var(--gradient-cyber);
            color: white;
            box-shadow: var(--glow-purple);
        }

        .nav-link i {
            width: 1.25rem;
            font-size: 1.125rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .content-shifted {
            margin-left: 0;
        }

        /* Top Header */
        .top-header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .breadcrumb {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-stats {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
        }

        .stat-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        .stat-icon.projects { background: var(--gradient-cyber); }
        .stat-icon.gallery { background: var(--gradient-success); }
        .stat-icon.articles { background: var(--gradient-warning); }
        .stat-icon.messages { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* Modern Cards */
        .modern-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-cyber);
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(139, 92, 246, 0.3);
        }

        /* Dashboard Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .stat-card.projects::before { background: var(--gradient-cyber); }
        .stat-card.gallery::before { background: var(--gradient-success); }
        .stat-card.articles::before { background: var(--gradient-warning); }
        .stat-card.messages::before { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: between;
            margin-bottom: 1.5rem;
        }

        .stat-icon-large {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-icon-large.projects { background: var(--gradient-cyber); }
        .stat-icon-large.gallery { background: var(--gradient-success); }
        .stat-icon-large.articles { background: var(--gradient-warning); }
        .stat-icon-large.messages { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            font-size: 1rem;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--neon-green);
            margin-top: 0.5rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .btn-modern {
            background: var(--gradient-cyber);
            border: none;
            color: white;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow-purple);
            color: white;
            text-decoration: none;
        }

        .btn-modern.secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-modern.secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.1);
        }

        /* Tables */
        .modern-table {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            overflow: hidden;
        }

        .table-header {
            background: rgba(139, 92, 246, 0.2);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin: 0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .table th,
        .table td {
            padding: 1rem 2rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table th {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            color: rgba(255, 255, 255, 0.9);
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .modern-sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-visible .modern-sidebar {
                transform: translateX(0);
            }

            .header-stats {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .top-header {
                padding: 1rem;
            }

            .content-area {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Animations */
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 5px rgba(139, 92, 246, 0.5); }
            50% { box-shadow: 0 0 20px rgba(139, 92, 246, 0.8), 0 0 30px rgba(139, 92, 246, 0.4); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 2rem;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dark-mode-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .skeleton {
            background: linear-gradient(90deg, rgba(255,255,255,0.1) 25%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.1) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Success States */
        .success-badge {
            background: var(--gradient-success);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .warning-badge {
            background: var(--gradient-warning);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .danger-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- Modern Sidebar -->
        <div class="modern-sidebar" id="sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <a href="{{ route('dashboard.index') }}" class="sidebar-brand">
                    <img src="{{ asset('logo/' . $konf->logo_setting) }}" alt="Logo">
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">{{ $konf->instansi_setting }}</div>
                        <div style="font-size: 0.75rem; opacity: 0.7;">Admin Dashboard</div>
                    </div>
                </a>
            </div>

            <!-- User Section -->
            <div class="sidebar-user">
                <div class="user-card">
                    <div class="user-avatar">
                        {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'GU' }}
                    </div>
                    <div class="user-info">
                        <h6>{{ Auth::check() ? Auth::user()->name : 'Guest User' }}</h6>
                        <small>Digital Transformation Consultant</small>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Dashboard</div>
                    <div class="nav-item">
                        <a href="{{ route('dashboard.index') }}"
                           class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics Overview</span>
                        </a>
                    </div>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Content Management</div>
                    <div class="nav-item">
                        <a href="{{ route('setting.index') }}"
                           class="nav-link {{ request()->routeIs('setting.*') ? 'active' : '' }}">
                            <i class="fas fa-user-circle"></i>
                            <span>Personal Profile</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('project.index') }}"
                           class="nav-link {{ request()->routeIs('project.*') ? 'active' : '' }}">
                            <i class="fas fa-briefcase"></i>
                            <span>Portfolio Projects</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('layanan.index') }}"
                           class="nav-link {{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                            <i class="fas fa-cogs"></i>
                            <span>Services & Solutions</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('testimonial.index') }}"
                           class="nav-link {{ request()->routeIs('testimonial.*') ? 'active' : '' }}">
                            <i class="fas fa-star"></i>
                            <span>Client Testimonials</span>
                        </a>
                    </div>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Media & Content</div>
                    <div class="nav-item">
                        <a href="{{ route('galeri.index') }}"
                           class="nav-link {{ request()->routeIs('galeri.*') ? 'active' : '' }}">
                            <i class="fas fa-images"></i>
                            <span>Media Gallery</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('berita.index') }}"
                           class="nav-link {{ request()->routeIs('berita.*') ? 'active' : '' }}">
                            <i class="fas fa-newspaper"></i>
                            <span>Articles & Insights</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('award.index') }}"
                           class="nav-link {{ request()->routeIs('award.*') ? 'active' : '' }}">
                            <i class="fas fa-trophy"></i>
                            <span>Awards & Recognition</span>
                        </a>
                    </div>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Business Operations</div>
                    <div class="nav-item">
                        <a href="{{ route('contacts.index') }}"
                           class="nav-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                            <i class="fas fa-envelope"></i>
                            <span>Client Messages</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Top Header -->
            <div class="top-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="breadcrumb">
                        <span>Digital Transformation Admin</span>
                    </div>
                </div>

                <div class="header-right">
                    <div class="header-stats">
                        @if(isset($countProject))
                        <div class="stat-item">
                            <div class="stat-icon projects">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $countProject ?? 0 }}</div>
                                <div style="opacity: 0.7;">Projects</div>
                            </div>
                        </div>
                        @endif

                        @if(isset($countGaleri))
                        <div class="stat-item">
                            <div class="stat-icon gallery">
                                <i class="fas fa-images"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $countGaleri ?? 0 }}</div>
                                <div style="opacity: 0.7;">Media</div>
                            </div>
                        </div>
                        @endif

                        @if(isset($countBerita))
                        <div class="stat-item">
                            <div class="stat-icon articles">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $countBerita ?? 0 }}</div>
                                <div style="opacity: 0.7;">Articles</div>
                            </div>
                        </div>
                        @endif

                        @if(isset($countPesan))
                        <div class="stat-item">
                            <div class="stat-icon messages">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $countPesan ?? 0 }}</div>
                                <div style="opacity: 0.7;">Messages</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="dark-mode-toggle" id="darkModeToggle">
                        <i class="fas fa-moon"></i>
                        <span style="font-size: 0.875rem;">Dark</span>
                    </div>

                    <!-- User Dropdown -->
                    <div class="user-dropdown" style="position: relative;">
                        <button class="user-avatar" style="border: none; background: var(--gradient-aurora); cursor: pointer;">
                            {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'GU' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const body = document.body;

            // Load saved sidebar state
            const sidebarState = localStorage.getItem('adminSidebarState');
            if (sidebarState === 'hidden') {
                sidebar.classList.add('sidebar-hidden');
                mainContent.classList.add('content-shifted');
            }

            // Toggle sidebar
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-hidden');
                mainContent.classList.toggle('content-shifted');

                // Save state
                if (sidebar.classList.contains('sidebar-hidden')) {
                    localStorage.setItem('adminSidebarState', 'hidden');
                } else {
                    localStorage.setItem('adminSidebarState', 'visible');
                }
            });

            // Dark mode toggle (already dark by default)
            const darkModeToggle = document.getElementById('darkModeToggle');
            darkModeToggle.addEventListener('click', function() {
                // Could implement light mode variant here
                console.log('Dark mode toggle clicked');
            });

            // Add smooth animations to cards
            const cards = document.querySelectorAll('.modern-card, .stat-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Responsive sidebar handling
            function handleResize() {
                if (window.innerWidth <= 1024) {
                    sidebar.classList.add('sidebar-hidden');
                    mainContent.classList.add('content-shifted');
                } else {
                    const savedState = localStorage.getItem('adminSidebarState');
                    if (savedState !== 'hidden') {
                        sidebar.classList.remove('sidebar-hidden');
                        mainContent.classList.remove('content-shifted');
                    }
                }
            }

            window.addEventListener('resize', handleResize);
            handleResize(); // Initial check
        });

        // Add loading states to buttons
        document.querySelectorAll('.btn-modern').forEach(button => {
            button.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                this.disabled = true;

                // Simulate loading (remove this and handle actual loading in your forms)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1000);
            });
        });

        // Add success animations
        function showSuccessNotification(message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: var(--gradient-success);
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 0.75rem;
                box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
                z-index: 9999;
                font-weight: 600;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => document.body.removeChild(notification), 300);
            }, 3000);
        }

        // Example usage for success notifications
        // showSuccessNotification('Portfolio updated successfully!');
    </script>
</body>
</html>