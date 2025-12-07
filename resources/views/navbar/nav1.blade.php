<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Waggy')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background-color: #292D37;
            overflow: hidden;
        }

        .home-nav {
            background-color: #282C36;
            height: 70px;
        }

        .home-navlinks {
            color: rgba(255, 255, 255, 0.7);
            padding: 8px 16px;
            border-radius: 8px;
            transition: .2s;
            text-decoration: none;
        }

        .home-navlinks:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* ===== UPDATED: NO COLOR ===== */
        #left-sidebar {
            width: 280px;
            height: calc(100vh - 70px);
            position: fixed;
            top: 70px;
            left: 0;
            background-color: transparent;
            overflow-y: auto;
            z-index: 2;
            padding: 1.5rem;
        }

        /* ===== UPDATED: NO COLOR ===== */
        #right-sidebar {
            width: 300px;
            height: calc(100vh - 70px);
            position: fixed;
            top: 70px;
            right: 0;
            background-color: transparent;
            overflow-y: auto;
            padding: 1.5rem;
            z-index: 2;
        }

        .main-wrapper {
            position: absolute;
            top: 70px;
            left: 280px;
            right:
                @hasSection('right-sidebar')
                300px @else 0 @endif;
            height: calc(100vh - 70px);
            overflow-y: auto;
            padding: 1.5rem;
            background-color: #1B1E25;
        }
    </style>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="home-nav d-flex align-items-center px-4 justify-content-between" style="height:70px;">

        <!-- LOGO -->
        <a class="d-flex align-items-center text-decoration-none" href="{{ route('home') }}">
            <img src="{{ asset('assets/logo.png') }}" style="height:40px;" class="me-2">
            <div class="d-flex flex-column">
                <span class="text-white fw-semibold">Waggy</span>
                <small class="text-white" style="font-size:.7rem;">Community</small>
            </div>
        </a>

        <!-- NAV ICONS -->
        <div class="d-flex align-items-center gap-4 mx-auto">
            <a href="{{ route('home') }}" class="home-navlinks fs-4"><i class="bi bi-house"></i></a>
            <a href="{{ route('friend.requests') }}" class="home-navlinks fs-4"><i class="bi bi-person-add"></i></a>
            <a href="#" class="home-navlinks fs-4"><i class="bi bi-chat-dots"></i></a>
            <a href="{{ route('location') }}" class="home-navlinks fs-4"><i class="bi bi-geo-alt"></i></a>
            <a href="{{ route('notifications') }}" class="home-navlinks fs-4"><i class="bi bi-bell"></i></a>
        </div>

        <!-- PROFILE -->
        <div class="d-flex align-items-center gap-3">

            <button class="btn btn-link text-white p-0" style="font-size:20px; opacity:.7;">
                <i class="bi bi-search"></i>
            </button>

            <div class="position-relative">
                <a href="#" id="profileDropdown" class="d-flex align-items-center gap-2 text-decoration-none">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                        style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                    <i class="bi bi-chevron-down text-white" style="font-size:12px;"></i>
                </a>

                <ul id="dropdownMenu" class="dropdown-menu dropdown-menu-end" style="display:none;">
                    <li><a class="dropdown-item" href="{{ route('setting') }}"><i class="bi bi-gear me-2"></i>Settings
                        </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ================= LEFT SIDEBAR ================= -->
    <div id="left-sidebar">
        <a href="{{ route('profile') }}" class="d-flex align-items-center gap-3 text-decoration-none mb-4">
            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
            <div>
                <h5 class="text-white m-0">{{ auth()->user()->pet_name }}</h5>
                <small class="text-white">{{ auth()->user()->pet_breed }}</small>
            </div>
        </a>

        <a href="#" class="filter-link d-flex align-items-center gap-3 p-2 text-decoration-none rounded"
            data-interest="Breeding">
            <i class="bi bi-heart text-white fs-5"></i>
            <span class="text-white">Breeding</span>
        </a>

        <a href="#" class="filter-link d-flex align-items-center gap-3 p-2 text-decoration-none mt-3 rounded"
            data-interest="Playdate">
            <i class="bi bi-calendar-event text-white fs-5"></i>
            <span class="text-white">Play Date</span>
        </a>
    </div>

    <!-- ================= RIGHT SIDEBAR ================= -->
    <div id="right-sidebar">
        @yield('right-sidebar')
    </div>

    <!-- ================= MAIN CONTENT ================= -->
    <div class="main-wrapper">
        @yield('content')
    </div>


    <!-- ================= JS ================= -->
    <script>
        const links = document.querySelectorAll('#left-sidebar .filter-link');
        const posts = document.querySelectorAll('.post-container .post');

        links.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const interest = link.dataset.interest;
                if (!interest) return;

                posts.forEach(post => {
                    post.style.display = (post.dataset.interest === interest) ? 'block' : 'none';
                });
            });
        });

        const profileBtn = document.getElementById("profileDropdown");
        const menu = document.getElementById("dropdownMenu");

        profileBtn.onclick = function (e) {
            e.preventDefault();
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }

        document.addEventListener("click", function (e) {
            if (!profileBtn.contains(e.target)) menu.style.display = "none";
        });
    </script>

    @stack('scripts')

</body>

</html>