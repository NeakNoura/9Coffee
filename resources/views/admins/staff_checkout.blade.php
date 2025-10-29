<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @stack('styles')
</head>
<body style="background-color: #2e2b2b; color: #f5f5f5;">

    {{-- Navbar / Header --}}
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3e2f2f;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admins.dashboard') }}">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="container-fluid py-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center py-3" style="background-color: #3e2f2f; color: #ccc;">
        &copy; {{ date('Y') }} 9Coffee Admin
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
