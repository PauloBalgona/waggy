<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('admin-title', 'Admin Panel') - Waggy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f5f7fa;
            color: #333;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .admin-sidebar {
            width: 250px;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        .admin-sidebar h5 {
            font-weight: bold;
            color: #fff;
            margin-bottom: 5px;
        }
        .admin-sidebar small {
            color: rgba(255, 255, 255, 0.8);
        }
        .admin-nav {
            margin-top: 40px;
        }
        .admin-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 8px;
            transition: 0.3s;
            font-size: 14px;
        }
        .admin-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
        .admin-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.25);
            color: #fff;
            font-weight: 600;
        }
        .admin-nav hr {
            border-color: rgba(255, 255, 255, 0.2);
            margin: 20px 0;
        }
        .main-content {
            margin-left: 250px;
            padding: 40px;
            min-height: 100vh;
        }
        .top-bar {
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .top-bar h3 {
            margin: 0;
            color: #1e3a8a;
            font-weight: 600;
        }
        .top-bar .d-flex {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .top-bar small {
            color: #999;
            font-size: 13px;
        }
        .btn-logout {
            background-color: #1e3a8a;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: 0.3s;
        }
        .btn-logout:hover {
            background-color: #1e40af;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="mb-5">
                <h5>Admin Panel</h5>
                <small>{{ auth()->user()->name }}</small>
            </div>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-link @if(Route::currentRouteName() === 'admin.dashboard') active @endif">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="nav-link @if(Str::startsWith(Route::currentRouteName(), 'admin.users')) active @endif">
                    <i class="bi bi-people me-2"></i>Users
                </a>
                <a href="{{ route('admin.posts') }}" class="nav-link @if(Str::startsWith(Route::currentRouteName(), 'admin.posts')) active @endif">
                    <i class="bi bi-file-text me-2"></i>Posts
                </a>
                <a href="{{ route('admin.dogs') }}" class="nav-link @if(Str::startsWith(Route::currentRouteName(), 'admin.dogs')) active @endif">
                    <i class="bi bi-heart me-2"></i>Dogs
                </a>
                <a href="{{ route('admin.certificates') }}" class="nav-link @if(Str::startsWith(Route::currentRouteName(), 'admin.certificates')) active @endif">
                    <i class="bi bi-file-check me-2"></i>Certificates
                </a>
                <a href="{{ route('admin.settings') }}" class="nav-link @if(Route::currentRouteName() === 'admin.settings') active @endif">
                    <i class="bi bi-gear me-2"></i>Settings
                </a>
                <hr>
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
            </nav>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
        </aside>

        <!-- Main Content -->
        <main class="main-content w-100">
            <!-- Top Bar -->
            <div class="top-bar">
                <h3>@yield('admin-title', 'Dashboard')</h3>
                <div class="d-flex">
                    <small>{{ now()->format('l, d M Y') }}</small>
                </div>
            </div>

            <!-- Alerts -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Content -->
            @yield('admin-content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
