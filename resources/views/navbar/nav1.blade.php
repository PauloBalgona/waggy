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
            background-color: #1c2230;
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

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background-color: #ef4444;
            color: #ffffff;
            min-width: 18px;
            height: 18px;
            padding: 0 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            border-radius: 999px;
            line-height: 1;
            box-shadow: none;
            border: 1px solid rgba(0, 0, 0, 0.12);
        }

        /* Hamburger Menu Button */
        .hamburger-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 8px 12px;
            margin-right: auto;
        }

        .hamburger-toggle:hover {
            color: rgba(255, 255, 255, 0.8);
        }

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


        #left-sidebar>a.filter-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white !important;
        }

        #right-sidebar {
            width: 300px;
            height: calc(100vh - 70px);
            position: fixed;
            top: 70px;
            right: 0;
            overflow-y: auto;
            padding: 1.5rem;
            z-index: 2;
        }

        #right-sidebar>div {
            background-color: transparent;
            border-radius: 0;
            border: none;
            padding: 0;
            min-height: auto;
        }

        #right-sidebar h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 16px;
        }

        #dropdownMenu {
            position: fixed;
            top: 70px;
            right: 18px;
            z-index: 5000;
            display: none;
            min-width: 160px;
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
            background-color: #1c2230;
        }

        /* Mobile User Dropdown */
        #mobileUserDropdown {
            position: fixed;
            top: 70px;
            right: 12px;
            z-index: 5000;
            display: none;
            min-width: 180px;
            background: #282C36;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {

            /* Home Nav should be fixed at top */
            .home-nav {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 3000;
                height: 70px !important;
            }

            /* Main wrapper offset should be just 70px (only Nav Bar) */
            .main-wrapper {
                position: absolute !important;
                top: 70px !important;
                left: 0 !important;
                right: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                padding: 0 !important;
                margin: 0 !important;
                height: calc(100vh - 70px) !important;
            }

            /* Show mobile user dropdown instead of desktop */
            #dropdownMenu {
                display: none !important;
            }

            /* Hide desktop profile dropdown trigger on mobile */
            #profileDropdown {
                display: none !important;
            }

            /* Hide search on mobile */
            .home-nav .bi-search,
            #searchInputContainer,
            #searchDropdown {
                display: none !important;
            }
        }

        .app-toast {
            pointer-events: auto;
            min-width: 260px;
            max-width: 360px;
            background: #0f1724;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.06);
            padding: 12px 14px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.6);
            display: flex;
            gap: 10px;
            align-items: center;
            transform-origin: right bottom;
            opacity: 0;
            transform: translateY(8px) scale(.98);
            transition: opacity .18s ease, transform .18s ease;
        }

        .app-toast.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .app-toast .toast-title {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .app-toast .toast-body {
            font-size: 0.85rem;
            color: #cbd5e1;
        }

        /* ===== MOBILE SPECIFIC LAYOUT FIXES (Overrides) ===== */
        @media screen and (max-width: 768px) {

            /* REMOVE SIDEBARS VISUALLY */
            #left-sidebar,
            #right-sidebar {
                display: none !important;
            }

            /* REMOVE BOOTSTRAP CONTAINER LIMITS */
            .main-wrapper .container,
            .main-wrapper .container-fluid,
            .main-wrapper .row {
                width: 100% !important;
                max-width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin: 0 !important;
            }

            /* POSTS EDGE TO EDGE */
            .card,
            .post-card {
                width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }

            /* HIDE DESKTOP ELEMENTS */
            /* remove logo */
            .home-nav>a[href*="home"] {
                display: none !important;
            }
            /* White text and icons for mobile dropdown */
#mobileUserDropdown .dropdown-item {
    color: white !important;
}

#mobileUserDropdown .dropdown-item i {
    color: white !important;
}

#mobileUserDropdown .dropdown-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: white !important;
}

#mobileUserDropdown .dropdown-divider {
    border-color: rgba(255, 255, 255, 0.1);
}
        }
    </style>
</head>

<body>

    <nav class="home-nav d-flex align-items-center px-4 justify-content-between" style="height:70px;">

        <button class="hamburger-toggle" id="hamburgerBtn" onclick="toggleSidebars()">
            <i class="bi bi-list"></i>
        </button>

        <a class="d-flex align-items-center text-decoration-none" href="{{ route('home') }}">
            <img src="{{ asset('assets/logo.png') }}" style="height:40px;" class="me-2">
            <div class="d-flex flex-column">
                <span class="text-white fw-semibold">Waggy</span>
                <small class="text-white" style="font-size:.7rem;">Community</small>
            </div>
        </a>

        <div class="d-flex align-items-center gap-4 mx-auto position-relative">
            <a href="{{ route('home') }}" class="home-navlinks fs-4"><i class="bi bi-house"></i></a>
            <a href="{{ route('friend.requests') }}" class="home-navlinks fs-4" style="position: relative;">
                <i class="bi bi-person-add"></i>
                @if($friendRequestCount > 0)
                <span class="notification-badge">{{ $friendRequestCount }}</span>
                @endif
            </a>
            <a href="{{ route('messages') }}" class="home-navlinks fs-4" style="position: relative;">
                <i class="bi bi-chat-dots"></i>
                @if($messageCount > 0)
                <span class="notification-badge">{{ $messageCount }}</span>
                @endif
            </a>

            <a href="{{ route('notifications') }}" class="home-navlinks fs-4" style="position: relative;">
                <i class="bi bi-bell"></i>
                @if($notificationCount > 0)
                <span class="notification-badge">{{ $notificationCount }}</span>
                @endif
            </a>
        </div>

        <div id="toastContainer" aria-live="polite" aria-atomic="true"></div>

        <style>
            #toastContainer {
                position: fixed;
                right: 18px;
                bottom: 18px;
                z-index: 7000;
                display: flex;
                flex-direction: column;
                gap: 10px;
                align-items: flex-end;
                pointer-events: none;
            }
        </style>

        <script>
            // Lightweight global toast helper
            window.showToast = function (opts) {
                try {
                    const container = document.getElementById('toastContainer');
                    if (!container) return;

                    const id = 'toast-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
                    const el = document.createElement('div');
                    el.className = 'app-toast';
                    el.id = id;
                    el.innerHTML = `
                            <div style="width:44px; height:44px; flex-shrink:0; border-radius:8px; overflow:hidden; background:#18202b; display:flex; align-items:center; justify-content:center;">
                                ${opts.icon ? `<img src="${opts.icon}" style="width:100%; height:100%; object-fit:cover;">` : '<i class="bi bi-bell" style="font-size:18px;color:#9ca3af"></i>'}
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div class="toast-title">${opts.title || ''}</div>
                                <div class="toast-body">${opts.body || ''}</div>
                            </div>
                        `;

                    if (opts.click) {
                        el.style.cursor = 'pointer';
                        el.addEventListener('click', (e) => { opts.click(e); remove(); });
                    }

                    function remove() {
                        el.classList.remove('show');
                        setTimeout(() => { el.remove(); }, 220);
                    }

                    container.appendChild(el);
                    void el.offsetWidth;
                    el.classList.add('show');

                    const timeout = ('timeout' in opts) ? opts.timeout : 6000;
                    if (timeout > 0) setTimeout(remove, timeout);
                    return el;
                } catch (err) {
                    console.error('showToast error', err);
                }
            };
        </script>

        <div class="d-flex align-items-center gap-3">

            <div style="position: relative; display: flex; align-items: center; gap: 10px;">
                <div id="searchInputContainer" style="display:none;">
                    <input type="text" id="searchInput" placeholder="Search..."
                        style="padding:8px 12px; background-color:#f8f9fa; border:1px solid #dee2e6; border-radius:6px; color:white; font-size:13px; width:200px;"
                        onkeyup="searchUsers()" onkeypress="handleSearchKeypress(event)">
                </div>

                <button class="btn btn-link text-white p-0" onclick="toggleSearchInput()"
                    style="font-size:20px; opacity:.7; border:none; background:none; cursor:pointer;">
                    <i class="bi bi-search"></i>
                </button>

                <div id="searchDropdown"
                    style="display:none; position:absolute; top:50px; right:0; width:300px; background-color:white; border:1px solid #dee2e6; border-radius:8px; z-index:999; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    <div id="searchResults" style="padding:8px; max-height:350px; overflow-y:auto;"></div>
                </div>
            </div>

            <!-- Desktop Profile Dropdown -->
            <div class="position-relative d-none d-md-block">
                <a href="#" id="profileDropdown" class="d-flex align-items-center gap-2 text-decoration-none">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                        style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                    <i class="bi bi-chevron-down text-white" style="font-size:12px;"></i>
                </a>

                <ul id="dropdownMenu" class="dropdown-menu dropdown-menu-end" style="display:none;">
                    <li><a class="dropdown-item" href="{{ route('setting') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Mobile Profile Dropdown -->
            <div class="position-relative d-md-none">
                <a href="#" id="mobileProfileBtn" class="d-flex align-items-center">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                        style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                </a>

                <ul id="mobileUserDropdown" class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('setting') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </nav>

    <div id="left-sidebar">
        <a href="{{ route('profile') }}" class="d-flex align-items-center gap-3 text-decoration-none mb-4">
            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
            <div>
                <h5 class="text-white m-0">{{ auth()->user()->pet_name }}</h5>
                <small class="text-white">{{ auth()->user()->pet_breed }}</small>
            </div>
        </a>

        <a href="{{ route('home') }}" class="filter-link d-flex align-items-center gap-3 p-2 text-decoration-none rounded"
            data-interest="Breeding">
            <i class="bi bi-heart text-white fs-5"></i>
            <span class="text-white">Breeding</span>
        </a>

        <a href="{{ route('home') }}"
            class="filter-link d-flex align-items-center gap-3 p-2 text-decoration-none mt-3 rounded"
            data-interest="Playdate">
            <i class="bi bi-calendar-event text-white fs-5"></i>
            <span class="text-white">Playdate</span>
        </a>
    </div>

    <div id="right-sidebar">
        @yield('right-sidebar')
    </div>

    <div class="main-wrapper">
        @yield('content')
    </div>

    <script>
        const links = document.querySelectorAll('#left-sidebar .filter-link');

        links.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const interest = link.dataset.interest;
                if (!interest) return;
                window.location.href = "{{ route('home') }}?interest=" + encodeURIComponent(interest);
            });
        });

        // Desktop Profile Dropdown
        const profileBtn = document.getElementById("profileDropdown");
        const menu = document.getElementById("dropdownMenu");

        if (profileBtn && menu) {
            profileBtn.onclick = function (e) {
                e.preventDefault();
                menu.style.display = menu.style.display === "block" ? "none" : "block";
            }
        }

        // Mobile Profile Dropdown
        const mobileProfileBtn = document.getElementById("mobileProfileBtn");
        const mobileMenu = document.getElementById("mobileUserDropdown");

        if (mobileProfileBtn && mobileMenu) {
            mobileProfileBtn.onclick = function (e) {
                e.preventDefault();
                mobileMenu.style.display = mobileMenu.style.display === "block" ? "none" : "block";
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener("click", function (e) {
            if (profileBtn && menu && !profileBtn.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = "none";
            }
            if (mobileProfileBtn && mobileMenu && !mobileProfileBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.style.display = "none";
            }
        });

        // Hamburger Menu Toggle
        function toggleSidebars() {
            const leftSidebar = document.getElementById('left-sidebar');
            const rightSidebar = document.getElementById('right-sidebar');
            leftSidebar.classList.toggle('show');
            rightSidebar.classList.toggle('show');
        }

        // Close sidebars when clicking outside
        document.addEventListener('click', function (e) {
            const hamburger = document.getElementById('hamburgerBtn');
            const leftSidebar = document.getElementById('left-sidebar');
            const rightSidebar = document.getElementById('right-sidebar');

            if (hamburger && leftSidebar && rightSidebar) {
                if (!leftSidebar.contains(e.target) && !rightSidebar.contains(e.target) && !hamburger.contains(e.target)) {
                    leftSidebar.classList.remove('show');
                    rightSidebar.classList.remove('show');
                }
            }
        });

        // Search functions
        function toggleSearchInput() {
            const inputContainer = document.getElementById('searchInputContainer');
            const dropdown = document.getElementById('searchDropdown');

            if (inputContainer.style.display === 'none') {
                inputContainer.style.display = 'block';
                dropdown.style.display = 'none';
                document.getElementById('searchInput').focus();
            } else {
                inputContainer.style.display = 'none';
                dropdown.style.display = 'none';
                document.getElementById('searchInput').value = '';
                document.getElementById('searchResults').innerHTML = '';
            }
        }

        function closeSearch() {
            document.getElementById('searchInputContainer').style.display = 'none';
            document.getElementById('searchDropdown').style.display = 'none';
            document.getElementById('searchInput').value = '';
            document.getElementById('searchResults').innerHTML = '';
        }

        function handleSearchKeypress(event) {
            if (event.key === 'Escape') {
                closeSearch();
            }
        }

        function searchUsers() {
            const query = document.getElementById('searchInput').value.trim();
            const resultsDiv = document.getElementById('searchResults');
            const dropdown = document.getElementById('searchDropdown');

            if (!query || query.length < 1) {
                resultsDiv.innerHTML = '';
                dropdown.style.display = 'none';
                return;
            }

            dropdown.style.display = 'block';

            fetch(`/api/users/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(users => {
                    if (!users || users.length === 0) {
                        resultsDiv.innerHTML = '<div style="padding:10px 12px; color:#8b92a7; text-align:center; font-size:12px;">No users found</div>';
                        return;
                    }

                    resultsDiv.innerHTML = users.map(user => `
                        <div style="padding:8px 10px; border-bottom:1px solid #3a3f52; cursor:pointer; transition:0.2s; display:flex; align-items:center; gap:10px;" onclick="window.location.href='/profile/${user.id}'" onmouseover="this.style.backgroundColor='#1e2230'" onmouseout="this.style.backgroundColor='transparent'">
                            <img src="${user.avatar ? '/storage/' + user.avatar : '/assets/usericon.png'}" style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
                            <div style="flex:1; min-width:0;">
                                <p style="color:white; margin:0; font-size:12px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${user.pet_name || 'Unknown'}</p>
                                <small style="color:#8b92a7; margin:0; font-size:10px;">${user.pet_breed || 'No breed'}</small>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.innerHTML = '<div style="padding:10px 12px; color:#e74c3c; text-align:center; font-size:12px;">Error searching users</div>';
                });
        }

        // Close search when clicking outside
        document.addEventListener('click', function (e) {
            const searchContainer = document.querySelector('[style*="position: relative; display: flex"]');
            if (searchContainer && !e.target.closest('[style*="position: relative; display: flex"]')) {
                closeSearch();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeSearch();
            }
        });
    </script>

    @stack('scripts')

    <script>
        // Realtime listeners
        (function () {
            const userId = {{ auth()->id() ?? 'null' }};
            if (!userId) return;

            const attachListeners = () => {
                try {
                    const messagesBadgeSelector = 'a[href="{{ route('messages') }}"] .notification-badge';
                    const friendReqBadgeSelector = 'a[href="{{ route('friend.requests') }}"] .notification-badge';
                    const notifBadgeSelector = 'a[href="{{ route('notifications') }}"] .notification-badge';

                    const messagesBadge = document.querySelector(messagesBadgeSelector);
                    const friendReqBadge = document.querySelector(friendReqBadgeSelector);
                    const notifBadge = document.querySelector(notifBadgeSelector);

                    if (typeof window.Echo === 'undefined' || !window.Echo.private) {
                        setTimeout(attachListeners, 200);
                        return;
                    }

                    window.Echo.private(`user.${userId}`)
                        .listen('MessageSent', (e) => {
                            if (messagesBadge) {
                                const val = parseInt(messagesBadge.textContent || '0') || 0;
                                messagesBadge.textContent = val + 1;
                            }
                            try {
                                const sender = e.sender || (e.message && e.message.sender) || {};
                                const title = sender.pet_name ? `${sender.pet_name}` : 'New message';
                                const body = e.message || (e.message && e.message.message) || 'You have a new message';
                                const avatar = sender.avatar || null;

                                window.showToast({
                                    title: title,
                                    body: body.length > 120 ? body.substring(0, 120) + '...' : body,
                                    icon: avatar,
                                    timeout: 6000,
                                    click: function () { window.location.href = '{{ route('messages') }}'; }
                                });
                            } catch (err) { console.debug('Realtime message received', e); }
                        })
                        .notification((notification) => {
                            if (notifBadge) {
                                const val = parseInt(notifBadge.textContent || '0') || 0;
                                notifBadge.textContent = val + 1;
                            }
                        });

                    window.Echo.private(`user.${userId}`).listen('FriendRequestCreated', (e) => {
                        if (friendReqBadge) {
                            const val = parseInt(friendReqBadge.textContent || '0') || 0;
                            friendReqBadge.textContent = val + 1;
                        }
                    });
                } catch (err) {
                    setTimeout(attachListeners, 200);
                }
            };

            attachListeners();
        })();
    </script>

    <script>
        // Poll unread counts
        (function () {
            const userId = {{ auth()->id() ?? 'null' }};
            if (!userId) return;

            const ensureBadge = (parentSelector) => {
                const parent = document.querySelector(parentSelector);
                if (!parent) return null;
                let badge = parent.querySelector('.notification-badge');
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'notification-badge';
                    badge.style.position = 'absolute';
                    badge.style.top = '-6px';
                    badge.style.right = '-8px';
                    parent.appendChild(badge);
                }
                return badge;
            };

            let lastCounts = { friend: 0, message: 0, notif: 0 };

            async function fetchCounts() {
                try {
                    const res = await fetch('/api/unread-counts', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!res.ok) return;
                    const json = await res.json();

                    const friendBadge = ensureBadge('a[href="{{ route('friend.requests') }}"]');
                    const messagesBadge = ensureBadge('a[href="{{ route('messages') }}"]');
                    const notifBadge = ensureBadge('a[href="{{ route('notifications') }}"]');

                    const f = json.friend_request_count || 0;
                    const m = json.message_count || 0;
                    const n = json.notification_count || 0;

                    if (friendBadge) {
                        friendBadge.textContent = f > 0 ? f : '';
                        friendBadge.style.display = f > 0 ? 'flex' : 'none';
                    }
                    if (messagesBadge) {
                        messagesBadge.textContent = m > 0 ? m : '';
                        messagesBadge.style.display = m > 0 ? 'flex' : 'none';
                    }
                    if (notifBadge) {
                        notifBadge.textContent = n > 0 ? n : '';
                        notifBadge.style.display = n > 0 ? 'flex' : 'none';
                    }

                    if (n > lastCounts.notif && lastCounts.notif !== 0) {
                        try {
                            const r = await fetch('/notifications/unread-count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                            if (r.ok) {
                                const d = await r.json();
                                if (d.latest) {
                                    const actor = d.latest.actor || {};
                                    window.showToast({
                                        title: actor.pet_name || 'New notification',
                                        body: d.latest.message || '',
                                        icon: actor.avatar ? ('/storage/' + actor.avatar) : null,
                                        timeout: 5000,
                                        click: function () { window.location.href = '{{ route('notifications') }}'; }
                                    });
                                }
                            }
                        } catch (err) { console.debug('fetch latest notif failed', err); }
                    }

                    lastCounts = { friend: f, message: m, notif: n };
                } catch (err) {
                    console.debug('poll unread counts failed', err);
                }
            }

            fetchCounts();
            setInterval(fetchCounts, 5000);
        })();
    </script>