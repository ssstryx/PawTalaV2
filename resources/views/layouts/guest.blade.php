<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <!-- Custom Styles -->
        <style>
            body {
                background-color: #FAEBCF; /* Custom background color */
            }
            .logo-container {
                position: relative;

            }
            .logo-overlay {
                position: absolute;
                top: -75px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 2; 
            }
            .pawtala-title {
                font-family: 'Fredoka One', cursive;
                font-size: 2.5rem;
                color: #333;
            }
        </style>

        <!-- Scripts -->
    </head>
    <body class="font-sans antialiased">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-4 pb-4">
            <div class="w-100 logo-container" style="max-width: 28rem;">
                <div class="logo-overlay">
                    <a href="/">
                         <img src="{{ asset('images/LogoPawTala.png') }}" alt="Logo" class="image-fluid" style="width: 400px; height: auto; margin-top: -76px;">
                    </a>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-body p-4 pt-5"> <!-- Added pt-5 to make space for the logo -->
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
