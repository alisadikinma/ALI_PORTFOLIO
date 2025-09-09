<?php

use Illuminate\Support\Facades\DB;

$konf = DB::table('setting')->first();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $konf->instansi_setting }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('logo/' . $konf->logo_setting) }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('admin/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    
    <!-- CKEditor Dark Mode Stylesheet -->
    <link href="{{ asset('admin/css/ckeditor-dark.css') }}" rel="stylesheet">
    
    <!-- Aggressive Dark Mode Fixes -->
    <link href="{{ asset('admin/css/dark-mode-aggressive.css') }}" rel="stylesheet">
</head>




<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3" id="sidebar">
            <nav class="navbar bg-light navbar-light">
                <a href="#" class="navbar-brand mx-4 mb-3">
                    <small class="text-dark">{{ $konf->instansi_setting }}</small>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{ asset('logo/' . $konf->logo_setting) }}" alt=""
                            style="width: 40px; height: 40px;">
                        <div
                            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                    </div>
                </div>

                <div class="navbar-nav w-100">
                    <a href="{{ route('dashboard.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>

                    <a href="{{ route('setting.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('setting.*') ? 'active' : '' }}">
                        <i class="fas fa-info-circle me-2"></i>Tentang Saya
                    </a>

                    <a href="{{ route('project.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('project.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase me-2"></i>Portfolio
                    </a>

                    <a href="{{ route('layanan.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs me-2"></i>Services
                    </a>

                    <a href="{{ route('testimonial.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('testimonial.*') ? 'active' : '' }}">
                        <i class="fas fa-comments me-2"></i>Testimonial
                    </a>

                    <a href="{{ route('galeri.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('galeri.*') ? 'active' : '' }}">
                        <i class="fas fa-images me-2"></i>Gallery
                    </a>

                    <a href="{{ route('berita.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('berita.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper me-2"></i>Article
                    </a>

                    <a href="{{ route('award.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('award.*') ? 'active' : '' }}">
                        <i class="fas fa-trophy me-2"></i>Award
                    </a>

                    <a href="{{ route('contacts.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope me-2"></i>Contacts
                    </a>
                </div>


            </nav>
        </div>

        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="#" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="ms-4 d-none d-md-block">
                    <div class="text-dark" id="serverTime">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i:s') }}
                    </div>
                </div>

                <div class="form-check form-switch ms-4">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle">
                    <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
                </div>

                <!-- <div id="google_translate_element" class="ms-3"></div> -->

                <div class="navbar-nav align-items-center ms-auto">

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('logo/' . $konf->logo_setting) }}"
                                alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="{{ route('profile.show') }}" class="dropdown-item">My Profile</a>
                            @if (Auth::user()->id == 1)
                            <a href="{{ route('setting.index') }}" class="dropdown-item">Setting Web</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
              this.closest('form').submit();"
                                    class="dropdown-item">Logout</a>
                            </form>
                        </div>
                    </div>
                </div>

            </nav>
            <!-- Navbar End -->


            @yield('content')


            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">{{ $konf->instansi_setting }}</a>, All Right Reserved.
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                            Designed By <a href="https://instagram.com/roberth_colln">@roberth_colln</a>

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
    <script src="{{ asset('admin/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('admin/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('admin/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('admin/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('admin/js/main.js') }}"></script>
    
    <!-- Dark Mode Handler -->
    <script src="{{ asset('admin/js/dark-mode.js') }}"></script>
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


</body>

</html>