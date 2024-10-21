<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Aplikasi Surat')</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- SweetAlert JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- <style>
        body {
            background-color: #f8f9fa; /* Warna latar belakang lembut */
            font-family: 'Arial', sans-serif; /* Font yang lebih modern */
            color: #333; /* Warna teks utama */
        }

        .navbar {
            background-color: #bcbe3b; /* Warna navbar */
            box-shadow: 0 2px 10px rgba(59, 206, 108, 0.1); /* Bayangan navbar */
        }

        .navbar-brand {
            color: rgb(53, 72, 156) !important; /* Warna teks logo */
            font-weight: bold; /* Berat font logo */
            font-size: 1.5rem; /* Ukuran font logo */
        }

        .navbar-nav .nav-link {
            color: rgb(53, 72, 156) !important; /* Warna teks link */
            transition: color 0.3s; /* Efek transisi */
            padding: 15px 20px; /* Padding pada link */
        }

        .navbar-nav .nav-link:hover {
            color: #ffdd57 !important; /* Warna teks saat hover */
            background-color: rgba(255, 255, 255, 0.1); /* Latar belakang saat hover */
            border-radius: 5px; /* Sudut melengkung saat hover */
        }

        .alert {
            border-radius: 5px; /* Sudut melengkung */
            padding: 15px; /* Padding lebih */
            margin-top: 20px; /* Margin atas untuk jarak */
        }

        .footer {
            background-color: #bcbe3b; /* Warna footer */
            border-top: 1px solid #ced4da; /* Garis atas footer */
            margin-top: 40px; /* Margin atas untuk jarak */
        }

        .copyright {
            font-size: 16px; /* Ukuran font lebih besar */
            color: #495057; /* Warna teks copyright */
        }

        .btn-link {
            text-decoration: none; /* Menghilangkan garis bawah pada tombol link */
        }

        /* Style untuk tombol logout */
        .btn-link:hover {
            color: #dc3545; /* Warna tombol logout saat hover */
            text-decoration: underline; /* Garis bawah saat hover */
        }

        /* Efek transisi pada tombol */
        .btn {
            transition: background-color 0.3s, color 0.3s; /* Efek transisi */
        }

        .btn:hover {
            background-color: #0056b3; /* Warna tombol saat hover */
            color: #bcbe3b; /* Warna teks tombol saat hover */
        }

        /* Gaya untuk konten utama */
        .container {
            background: white; /* Latar belakang putih untuk konten utama */
            border-radius: 8px; /* Sudut melengkung pada konten */
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1); /* Bayangan pada konten */
            padding: 20px; /* Padding di dalam konten */
            margin-top: 20px; /* Margin atas untuk jarak */
        }
    </style> --}}
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('image/perpus.png') }}" alt="Logo" style="height: 40px; margin-right: 10px;">
                </div>
                <div class="mx-auto text-center">
                    <span class="navbar-brand">Sistem Penomoran Surat</span>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    @auth
                        <li class="nav-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link" style="color: inherit;">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer py-3">
        <div class="container text-center">
            <span class="text-muted">
                <div class="copyright">&copy; Andriani Silviana Primastuti {{ date('Y') }} Sistem Penomoran Surat</div>
            </span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
