<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>404 - Page Not Found</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <!--  -->

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset ('web/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset ('web/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset ('web/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset ('web/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset ('web/css/style.css') }}" rel="stylesheet">
    <style>
        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }

            100% {
                transform: translateX(0);
            }
        }

        .shake-animation {
            animation: shake 0.5s ease-in-out infinite;
        }
    </style>
</head>

<body>
    


    <!-- 404 Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <i class="bi bi-exclamation-triangle display-1 text-primary"></i>
                    <h1 class="display-1">404</h1>
                    <h1 class="mb-4">Page Not Found</h1>
                    <p class="mb-4">Silahkan Update Data Anggota Anda !!</p>
                    <a class="btn btn-primary rounded-pill py-3 px-5" href="{{url ('anggota')}}">Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <!-- 404 End -->
        

    <!-- Footer Start -->
    


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset ('web/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset ('web/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset ('web/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset ('web/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset ('web/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset ('web/lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset ('web/js/main.js') }}"></script>
</body>

</html>