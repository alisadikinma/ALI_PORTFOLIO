<?php

use Illuminate\Support\Facades\DB;

$konf = DB::table('setting')->first();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo e($konf->instansi_setting); ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="<?php echo e(asset('logo/' . $konf->logo_setting)); ?>" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?php echo e(asset('admin/lib/owlcarousel/assets/owl.carousel.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css')); ?>" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?php echo e(asset('admin/css/bootstrap.min.css')); ?>" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?php echo e(asset('admin/css/style.css')); ?>" rel="stylesheet">
    
    <!-- CKEditor Dark Mode Stylesheet -->
    <link href="<?php echo e(asset('admin/css/ckeditor-dark.css')); ?>" rel="stylesheet">
    
    <!-- Aggressive Dark Mode Fixes -->
    <link href="<?php echo e(asset('admin/css/dark-mode-aggressive.css')); ?>" rel="stylesheet">
    
    <!-- Admin Clean Fixes -->
    <link href="<?php echo e(asset('admin/css/admin-clean.css')); ?>" rel="stylesheet">
    
    <!-- Custom CSS Force Layout Fix -->
    <style>
        /* Override default template styles */
        body {
            overflow-x: hidden !important;
        }
        
        /* Reset container styles */
        .container-xxl {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            display: block !important;
        }
        
        /* Override Bootstrap container-fluid as well */
        .container-fluid {
            max-width: 100% !important;
            width: 100% !important;
        }
        
        /* Hamburger button positioning */
        .sidebar-toggler {
            position: fixed !important;
            left: 15px !important;
            top: 15px !important;
            z-index: 1050 !important;
            background: #fff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.25rem !important;
            padding: 0.5rem 0.75rem !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075) !important;
            transition: all 0.3s ease !important;
            width: 40px !important;
            height: 40px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .sidebar-toggler:hover {
            background: #f8f9fa !important;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1) !important;
        }
        
        /* Dark mode support for hamburger */
        body.dark .sidebar-toggler {
            background: #2c3034 !important;
            border-color: #3a3f44 !important;
            color: #fff !important;
        }
        
        body.dark .sidebar-toggler:hover {
            background: #3a3f44 !important;
        }
        
        /* Override template default content styles */
        .content {
            position: relative !important;
            left: 0 !important;
            right: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin-left: 0 !important;
            min-height: 100vh !important;
            transition: all 0.3s ease !important;
        }
        
        /* Override template's open class */
        .content.open {
            width: 100% !important;
            margin-left: 0 !important;
        }
        
        /* When sidebar is visible */
        .sidebar-visible .content {
            margin-left: 280px !important;
            width: calc(100% - 280px) !important;
        }
        
        /* Sidebar styles */
        .sidebar {
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            left: -280px !important;
            width: 280px !important;
            height: 100vh !important;
            min-height: 100vh !important;
            transition: all 0.3s ease !important;
            z-index: 1040 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            background: #fff !important;
            padding-bottom: 0 !important;
        }
        
        .sidebar-visible .sidebar {
            left: 0 !important;
        }
        
        /* Dark mode sidebar background */
        body.dark .sidebar,
        body.dark-mode .sidebar {
            background: #2a2a3d !important;
        }
        
        /* Fix sidebar navbar alignment */
        .sidebar .navbar {
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
            height: 100vh !important;
        }
        
        /* Add padding to brand area to avoid hamburger overlap */
        .sidebar .navbar-brand {
            margin-left: 55px !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            margin-bottom: 20px !important;
            display: block !important;
            width: auto !important;
            text-align: left !important;
        }
        
        /* User profile section */
        .sidebar .d-flex.align-items-center {
            padding-left: 20px !important;
            padding-right: 20px !important;
        }
        
        .sidebar .navbar-nav {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        
        .sidebar .nav-item {
            display: block !important;
            width: 100% !important;
        }
        
        .sidebar .nav-link {
            display: flex !important;
            align-items: center !important;
            padding: 0.75rem 1rem !important;
            width: 100% !important;
        }
        
        /* Top Navbar adjustments */
        .content > .navbar {
            padding: 0 !important;
            margin: 0 !important;
            padding-left: 70px !important; /* Space for hamburger button */
        }
        
        /* Main content area */
        .main-content {
            padding: 1.5rem !important;
            min-height: calc(100vh - 120px) !important;
            width: 100% !important;
        }
        
        /* Mobile overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1039;
        }
        
        /* Override template's responsive styles */
        @media (min-width: 992px) {
            .sidebar {
                margin-left: 0 !important;
            }
            
            .sidebar.open {
                margin-left: 0 !important;
            }
            
            .content {
                width: 100% !important;
                margin-left: 0 !important;
            }
            
            .sidebar-visible .content {
                width: calc(100% - 280px) !important;
                margin-left: 280px !important;
            }
        }
        
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: 0 !important;
            }
            
            .content {
                width: 100% !important;
                margin-left: 0 !important;
            }
            
            .sidebar-visible .content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .sidebar-visible .sidebar-overlay {
                display: block !important;
            }
        }
    </style>
</head>




<body class="sidebar-visible admin-panel">
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar Start -->
        <div class="sidebar" id="sidebar">
            <nav class="navbar bg-light navbar-light h-100 pe-4 pb-3">
                <a href="#" class="navbar-brand mb-3">
                    <small class="text-dark">&nbsp;&nbsp;<?php echo e($konf->instansi_setting); ?></small>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="<?php echo e(asset('logo/' . $konf->logo_setting)); ?>" alt=""
                            style="width: 40px; height: 40px;">
                        <div
                            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo e(Auth::check() ? Auth::user()->name : 'Guest User'); ?></h6>
                    </div>
                </div>

                <div class="navbar-nav w-100">
                    <a href="<?php echo e(route('dashboard.index')); ?>"
                        class="nav-item nav-link <?php echo e(request()->routeIs('dashboard.index') ? 'active' : ''); ?>">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>

                    <a href="<?php echo e(route('setting.index')); ?>"
                        class="nav-item nav-link <?php echo e(request()->routeIs('setting.*') ? 'active' : ''); ?>">
                        <i class="fas fa-info-circle me-2"></i>Tentang Saya
                    </a>

                    <a href="<?php echo e(route('project.index')); ?>"
                        class="nav-item nav-link <?php echo e(request()->routeIs('project.*') ? 'active' : ''); ?>">
                        <i class="fas fa-briefcase me-2"></i>Portfolio
                    </a>

                    <a href="<?php echo e(route('layanan.index')); ?>"
                        class="nav-item nav-link <?php echo e(request()->routeIs('layanan.*') ? 'active' : ''); ?>">
                        <i class="fas fa-cogs me-2"></i>Services
                    </a>

                    <a href="<?php echo e(route('testimonial.index')); ?>"
                        class="nav-item nav-link <?php echo e(request()->routeIs('testimonial.*') ? 'active' : ''); ?>">
                        <i class="fas fa-comments me-2"></i>Testimonial
                    </a>

                    <a href="<?php echo e(route('galeri.index')); ?>"
                        class="nav-item nav-link <?php echo e(request()->routeIs('galeri.*') ? 'active' : ''); ?>">
                        <i class="fas fa-images me-2"></i>Gallery
                    </a>

                    <a href="<?php echo e(route('berita.index')); ?>"
                        class="nav-item nav-link <?php echo e(request()->routeIs('berita.*') ? 'active' : ''); ?>">
                        <i class="fas fa-newspaper me-2"></i>Article
                    </a>

                    <a href="<?php echo e(route('award.index')); ?>"
                        class="nav-item nav-link <?php echo e(request()->routeIs('award.*') ? 'active' : ''); ?>">
                        <i class="fas fa-trophy me-2"></i>Award
                    </a>

                    <a href="<?php echo e(route('contacts.index')); ?>"
                        class="nav-item nav-link <?php echo e(request()->routeIs('contacts.*') ? 'active' : ''); ?>">
                        <i class="fas fa-envelope me-2"></i>Contacts
                    </a>
                </div>


            </nav>
        </div>

        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Hamburger Toggle Button (Outside Navbar) -->
            <a href="#" class="sidebar-toggler" id="sidebarToggler">
                <i class="fa fa-bars"></i>
            </a>
            
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top py-0">
                <div class="d-flex align-items-center w-100">
                    <div style="flex: 1;">
                        <div class="text-dark d-none d-md-block" id="serverTime">
                            <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i:s')); ?>

                        </div>
                    </div>

                    <div class="form-check form-switch ms-4">
                        <input class="form-check-input" type="checkbox" id="darkModeToggle">
                        <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
                    </div>

                    <div class="navbar-nav align-items-center ms-auto">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <img class="rounded-circle me-lg-2" src="<?php echo e(asset('logo/' . $konf->logo_setting)); ?>"
                                    alt="" style="width: 40px; height: 40px;">
                                <span class="d-none d-lg-inline-flex"><?php echo e(Auth::check() ? Auth::user()->name : 'Guest User'); ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                                <?php if(Auth::check()): ?>
                                    <a href="<?php echo e(route('profile.show')); ?>" class="dropdown-item">My Profile</a>
                                    <?php if(Auth::user()->id == 1): ?>
                                    <a href="<?php echo e(route('setting.index')); ?>" class="dropdown-item">Setting Web</a>
                                    <?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <a href="<?php echo e(route('logout')); ?>"
                                            onclick="event.preventDefault();
                      this.closest('form').submit();"
                                            class="dropdown-item">Logout</a>
                                    </form>
                                <?php else: ?>
                                    <a href="#" class="dropdown-item">Guest Mode</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </nav>
            <!-- Navbar End -->

            <!-- Main Content Area -->
            <div class="main-content">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#"><?php echo e($konf->instansi_setting); ?></a>, All Right Reserved.
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                            Designed By <a href="https://instagram.com/alisadikinma">@alisadikinma</a>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('admin/lib/chart/chart.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/lib/easing/easing.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/lib/waypoints/waypoints.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/lib/owlcarousel/owl.carousel.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/lib/tempusdominus/js/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/lib/tempusdominus/js/moment-timezone.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js')); ?>"></script>

    <!-- Template Javascript -->
    <script src="<?php echo e(asset('admin/js/main.js')); ?>"></script>
    
    <!-- Dark Mode Handler -->
    <script src="<?php echo e(asset('admin/js/dark-mode.js')); ?>"></script>
    
    <!-- Admin Cleanup -->
    <script src="<?php echo e(asset('admin/js/admin-cleanup.js')); ?>"></script>
    
    <!-- Sidebar Toggle Handler -->
    <script>
        // Disable default sidebar toggler from main.js
        $(document).ready(function() {
            $('.sidebar-toggler').off('click');
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.getElementById('sidebarToggler');
            const body = document.body;
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            
            // Remove any 'open' classes from template
            sidebar.classList.remove('open');
            content.classList.remove('open');
            
            // Load saved state from localStorage
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'hidden') {
                body.classList.remove('sidebar-visible');
            }
            
            // Toggle sidebar on click
            sidebarToggler.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                body.classList.toggle('sidebar-visible');
                
                // Save state to localStorage
                if (body.classList.contains('sidebar-visible')) {
                    localStorage.setItem('sidebarState', 'visible');
                } else {
                    localStorage.setItem('sidebarState', 'hidden');
                }
            });
            
            // Handle overlay click on mobile
            const overlay = document.getElementById('sidebarOverlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    body.classList.remove('sidebar-visible');
                    localStorage.setItem('sidebarState', 'hidden');
                });
            }
        });
    </script>
    
    <script>
        function updateTime() {
            const serverTimeElement = document.getElementById('serverTime');
            if (!serverTimeElement) return;

            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const date = now.toLocaleDateString('id-ID', options);
            const time = now.toLocaleTimeString('id-ID');

            serverTimeElement.textContent = `${date} - ${time}`;
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'id,en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- Global Gallery Components -->
    <?php echo $__env->make('partials.global-image-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.global-gallery-loader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>

</html><?php /**PATH C:\xampp\htdocs\ALI_PORTFOLIO\resources\views/layouts/index.blade.php ENDPATH**/ ?>