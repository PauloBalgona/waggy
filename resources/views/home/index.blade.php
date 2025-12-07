@extends('navbar.nav1')
@section('title', 'Home - Waggy')
@section('body-class', 'bg-gray-900')

@section('content')

    <style>
        .left-sidebar,
        .right-sidebar,
        aside,
        nav.sidebar,
        #left-sidebar,
        #right-sidebar {
            background-color: #1B1E25 !important;
        }

        * {
            border-left: none !important;
            border-right: none !important;
        }

        .modal-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content-custom {
            background: linear-gradient(145deg, #2a2e38, #252933);
            border-radius: 16px;
            padding: 28px;
            width: 90%;
            max-width: 420px;
            max-height: 70vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5),
                0 0 1px rgba(255, 255, 255, 0.1) inset;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-content-custom::-webkit-scrollbar {
            width: 6px;
        }

        .modal-content-custom::-webkit-scrollbar-track {
            background: transparent;
        }

        .modal-content-custom::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .modal-content-custom::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-content-custom h5 {
            color: white;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .modal-content-custom ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .modal-content-custom ul li {
            padding: 14px 18px;
            color: #e0e0e0;
            background-color: rgba(255, 255, 255, 0.03);
            margin-bottom: 8px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            font-weight: 500;
        }

        .modal-content-custom ul li:hover {
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .modal-content-custom ul li:active {
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.12);
            transform: translateX(2px) scale(0.98);
        }

        .modal-content-custom .btn-close {
            position: absolute;
            right: 30px;
            opacity: 0.6;
        }

        #filter-section .btn-link {
            text-decoration: none !important;
        }

        #filter-section .btn-link:hover {
            text-decoration: none !important;
        }

        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        *::-webkit-scrollbar {
            display: none;
        }
    </style>
    <div class="min-h-screen flex">

        <!-- MAIN CONTENT -->
        <div class="post-container" style="max-width:70%; margin-left:110px; display:flex; flex-direction:column; gap:5px;">

            <!-- POST INPUT SECTION -->
            <section class="post p-4 mt-0"
                style="background-color:#292D37; border-radius:5px; position: relative; margin-bottom: 10px;">

                <div class="d-flex align-items-start mb-3">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                        alt="Profile" class="rounded-circle me-3 mt-1"
                        style="width:50px; height:50px; object-fit:cover; background:#333;">

                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="w-100">
                        @csrf

                        <div class="d-flex align-items-center w-100 gap-3">

                            <!-- POSTING PAGE -->
                            <div onclick="window.location.href='{{ route('posting.page') }}'"
                                style="flex:1; padding:13px 14px; background:#1B1E25;
                                                                                                                                                                                                                                                    border-radius:12px; color:#adb5bd; min-height:55px;
                                                                                                                                                                                                                                                    border:none; outline:none; resize:none; cursor:pointer;">
                                What's on your mind?
                            </div>

                            <!-- IMAGE REDIRECT -->
                            <div id="photoBtn" class="text-white d-flex align-items-center justify-content-center"
                                style="cursor:pointer; width:53px; height:55px; background:#1B1E25; border-radius:12px;">
                                <i class="bi bi-image" style="font-size:26px;"></i>
                            </div>

                        </div>

                        <!-- HIDDEN REAL TEXTAREA -->
                        <textarea name="content" id="post-content" style="display:none;"></textarea>

                        <!-- POST PAGE -->
                        <input type="file" name="image" id="post-image" accept="image/*" style="display:none;">

                    </form>

                </div>

                <!-- Filters -->
                <div id="filter-section" class="filter-container d-flex justify-content-around p-2">
                    <div class="position-relative">
                        <button id="filter-age" onclick="showAgeModal()"
                            class="btn btn-link text-white d-flex align-items-center gap-1">
                            <i class="bi bi-calendar fs-4"></i><span id="age-text">Age</span>
                        </button>
                    </div>

                    <div class="position-relative">
                        <button id="filter-breed" onclick="showBreedModal()"
                            class="btn btn-link text-white d-flex align-items-center gap-1">
                            <i class="bi bi-tag fs-4"></i><span id="breed-text">Breed</span>
                        </button>
                    </div>

                    <!-- LOCATION FILTER WITH MODAL -->
                    <div class="position-relative">
                        <button id="filter-location" onclick="showCityModal()"
                            class="btn btn-link text-white d-flex align-items-center gap-1">
                            <i class="bi bi-geo-alt fs-4"></i><span id="location-text">Location</span>
                        </button>
                    </div>

                    <!-- AUDIENCE FILTER WITH MODAL -->
                    <div class="position-relative">
                        <button id="filter-type" onclick="showAudienceModal()"
                            class="btn btn-link text-white d-flex align-items-center gap-1">
                            <i class="bi bi-funnel fs-4"></i>
                            <span id="filter-type-text">Audience</span>
                        </button>
                    </div>
                </div>

            </section>

            <!--     POSTS LOOP   -->
            @foreach ($posts as $post)

                <div class="post mb-4" data-interest="{{ $post->interest }}"
                    style="background-color:#292D37; border-radius:8px; overflow:hidden; max-width:800px;">

                    {{-- POST HEADER (User Info) --}}
                    <div class="p-3 d-flex align-items-center gap-3 border-bottom" style="border-color:#1B1E25;">

                        <a href="{{ route('profile.show', $post->user->id) }}"
                            class="d-flex align-items-center gap-3 text-decoration-none">

                            <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('assets/usericon.png') }}"
                                class="rounded-circle" style="width:40px; height:40px; object-fit:cover;">

                            <div>
                                <h6 class="text-white mb-0" style="font-size:14px;">
                                    {{ $post->user->pet_name ?? 'Unknown User' }}
                                </h6>
                                <small class="text-white mb-0" style="font-size:12px;">
                                    {{ $post->created_at->diffForHumans() }}
                                </small>
                            </div>

                        </a>

                        <div class="ms-auto position-relative">
                            <button class="btn btn-link text-white p-0" onclick="toggleMenu({{ $post->id }})">
                                <i class="bi bi-three-dots-vertical fs-5"></i>
                            </button>

                            <div id="menu-{{ $post->id }}" class="dropdown-menu-custom"
                                style="position:absolute; right:0; top:30px; background:#1B1E25; border-radius:8px; width:150px; display:none;">

                                @if($post->user_id === auth()->id())
                                    <!-- DELETE -->
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item-custom text-danger">Delete</button>
                                    </form>
                                @endif

                                <!-- REPORT -->
                                <form action="{{ route('posts.report', $post->id) }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item-custom">Report</button>
                                </form>

                                <!-- BLOCK -->
                                <form action="{{ route('user.block', $post->user_id) }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item-custom">Block</button>
                                </form>
                            </div>
                        </div>

                    </div>

                    {{-- MESSAGE --}}
                    @if($post->message)
                        <div class="p-3">
                            <p class="text-white mb-0" style="font-size:14px; line-height:1.5;">{{ $post->message }}</p>
                        </div>
                    @endif

                    {{-- PHOTO --}}
                    @if($post->photo)
                        <div class="w-100"
                            style="background-color:#1B1E25; max-height:450px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                            <img src="{{ asset('storage/' . $post->photo) }}"
                                style="width:100%; height:auto; max-height:450px; object-fit:cover; display:block;">
                        </div>
                    @endif

                    {{-- TAGS (AGE / BREED / LOCATION / INTEREST) --}}
                    @if($post->city || $post->age || $post->breed || $post->interest)
                        <div class="p-3 d-flex flex-wrap gap-2">

                            {{-- LOCATION (BLUE) --}}
                            @if($post->city && $post->province)
                                <span class="badge text-white d-flex align-items-center gap-1"
                                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                                    <i class="bi bi-geo-alt" style="color:#0dcaf0;"></i>
                                    {{ $post->city }}, {{ $post->province }}
                                </span>
                            @endif

                            {{-- AGE (YELLOW) --}}
                            @if($post->age)
                                <span class="badge text-white d-flex align-items-center gap-1"
                                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                                    <i class="bi bi-calendar" style="color:#ffc107;"></i>
                                    Age: {{ $post->age }}
                                </span>
                            @endif

                            {{-- BREED (GREEN) --}}
                            @if($post->breed)
                                <span class="badge text-white d-flex align-items-center gap-1"
                                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                                    <i class="bi bi-tag" style="color:#20c997;"></i>
                                    {{ $post->breed }}
                                </span>
                            @endif

                            {{-- INTEREST (RED HEART) --}}
                            @if($post->interest)
                                <span class="badge text-white d-flex align-items-center gap-1"
                                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                                    <i class="bi bi-heart" style="color:#ff4d6d;"></i>
                                    {{ $post->interest }}
                                </span>
                            @endif

                        </div>
                    @endif


                    {{-- ACTION BUTTONS --}}
                    <div class="d-flex justify-content-around border-top p-2" style="border-color:#1B1E25;">

                        {{-- LIKE BUTTON --}}
                        <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit"
                                class="btn btn-link d-flex align-items-center gap-2 {{ $post->likes->where('user_id', auth()->id())->count() > 0 ? 'text-primary' : 'text-white' }}"
                                style="font-size:13px; text-decoration:none;">
                                <i class="bi bi-heart" style="font-size:18px;"></i>
                                <span>{{ $post->likes_count }} Like{{ $post->likes_count != 1 ? 's' : '' }}</span>
                            </button>
                        </form>

                        {{-- COMMENT BUTTON --}}
                        <a href="{{ route('comments.index', $post->id) }}"
                            class="btn btn-link text-white d-flex align-items-center gap-2"
                            style="font-size:13px; text-decoration:none;">
                            <i class="bi bi-chat-dots" style="font-size:18px;"></i>
                            <span>Comment</span>
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

        @section('right-sidebar')
            <h6 class="text-white font-semibold mb-4">Contacts</h6>


        @endsection

    </div>

    <!-- Age Selection Modal -->
    <div id="ageModal" class="modal-container" style="display:none;">
        <div class="modal-content-custom position-relative">
            <button class="position-absolute top-0 end-0 m-2 btn-close btn-close-white" onclick="closeAgeModal()"></button>
            <h5>Select Age</h5>
            <ul id="ageList"></ul>
        </div>
    </div>

    <!-- Breed Selection Modal -->
    <div id="breedModal" class="modal-container" style="display:none;">
        <div class="modal-content-custom position-relative">
            <button class="position-absolute top-0 end-0 m-2 btn-close btn-close-white"
                onclick="closeBreedModal()"></button>
            <h5>Select Breed</h5>
            <ul id="breedList"></ul>
        </div>
    </div>

    <!-- City Selection Modal -->
    <div id="cityModal" class="modal-container" style="display:none;">
        <div class="modal-content-custom position-relative">
            <button class="position-absolute top-0 end-0 m-2 btn-close btn-close-white" onclick="closeCityModal()"></button>
            <h5 id="modal-title">Select City</h5>
            <ul id="cityList"></ul>
        </div>
    </div>

    <!-- Audience Selection Modal -->
    <div id="audienceModal" class="modal-container" style="display:none;">
        <div class="modal-content-custom position-relative">
            <button class="position-absolute top-0 end-0 m-2 btn-close btn-close-white"
                onclick="closeAudienceModal()"></button>
            <h5>Select Audience</h5>
            <ul id="audienceList"></ul>
        </div>
    </div>

    @push('scripts')
        <script>
            const locationData = {
                "Pampanga": ["Angeles City", "Mabalacat City", "San Fernando City", "Mexico", "Bacolor", "Guagua", "Porac", "Santa Rita", "Magalang"],
                "Cavite": ["Bacoor City", "Imus City", "Dasmariñas City", "Tagaytay City", "General Trias", "Trece Martires City", "Kawit", "Rosario", "Silang", "Tanza"],
                "Laguna": ["Calamba City", "Santa Rosa City", "Biñan City", "San Pedro City", "Cabuyao City", "San Pablo City", "Los Baños", "Pagsanjan", "Sta. Cruz", "Bay"]
            };

            const ages = [1, 2, 3, 4, 5];
            const breeds = ["Labrador", "Golden Retriever", "Pug", "Shih Tzu", "Pomeranian"];

            let selectedProvince = localStorage.getItem('selectedProvince') || '';
            let selectedCity = localStorage.getItem('selectedCity') || '';

            const urlParams = new URLSearchParams(window.location.search);
            const provinceParam = urlParams.get('province');
            const cityParam = urlParams.get('city');

            if (provinceParam) {
                selectedProvince = provinceParam.split(' (')[0];
                localStorage.setItem('selectedProvince', selectedProvince);
            }

            if (cityParam) {
                selectedCity = cityParam;
                localStorage.setItem('selectedCity', cityParam);
            }

            function updateLocationText() {
                const locationText = document.getElementById('location-text');
                locationText.textContent = 'Location';
            }

            function showCityModal() {
                if (!selectedProvince) {
                    window.location.href = '/location';
                    return;
                }

                const modal = document.getElementById('cityModal');
                const cityList = document.getElementById('cityList');
                const modalTitle = document.getElementById('modal-title');

                modalTitle.textContent = `Select City in ${selectedProvince}`;
                cityList.innerHTML = '';

                const cities = locationData[selectedProvince] || [];

                cities.forEach(city => {
                    const li = document.createElement('li');
                    li.textContent = city;
                    li.onclick = () => {
                        selectedCity = city;
                        localStorage.setItem('selectedCity', city);
                        updateLocationText();
                        closeCityModal();
                        window.location.href = `/home?province=${encodeURIComponent(selectedProvince)}&city=${encodeURIComponent(selectedCity)}`;
                    };
                    cityList.appendChild(li);
                });

                modal.style.display = 'flex';
            }

            function closeCityModal() {
                document.getElementById('cityModal').style.display = 'none';
            }

            function showAgeModal() {
                const modal = document.getElementById('ageModal');
                const ageList = document.getElementById('ageList');

                ageList.innerHTML = '';

                ages.forEach(age => {
                    const li = document.createElement('li');
                    li.textContent = age + " year(s)";
                    li.onclick = () => {
                        closeAgeModal();
                        window.location.href = `/home?age=${age}`;
                    };
                    ageList.appendChild(li);
                });

                modal.style.display = 'flex';
            }

            function closeAgeModal() {
                document.getElementById('ageModal').style.display = 'none';
            }

            function showBreedModal() {
                const modal = document.getElementById('breedModal');
                const breedList = document.getElementById('breedList');

                breedList.innerHTML = '';

                breeds.forEach(breed => {
                    const li = document.createElement('li');
                    li.textContent = breed;
                    li.onclick = () => {
                        closeBreedModal();
                        window.location.href = `/home?breed=${encodeURIComponent(breed)}`;
                    };
                    breedList.appendChild(li);
                });

                modal.style.display = 'flex';
            }

            function closeBreedModal() {
                document.getElementById('breedModal').style.display = 'none';
            }

            function showAudienceModal() {
                const modal = document.getElementById('audienceModal');
                const audienceList = document.getElementById('audienceList');

                audienceList.innerHTML = '';

                const audiences = [
                    { value: 'public', label: 'Public Posts' },
                    { value: 'friends', label: 'Friends Only' }
                ];

                audiences.forEach(audience => {
                    const li = document.createElement('li');
                    li.textContent = audience.label;
                    li.onclick = () => {
                        closeAudienceModal();
                        window.location.href = `/home?audience=${audience.value}`;
                    };
                    audienceList.appendChild(li);
                });

                modal.style.display = 'flex';
            }

            function closeAudienceModal() {
                document.getElementById('audienceModal').style.display = 'none';
            }

            updateLocationText();

            // Toggle menu for post options (Delete, Report, Block)
            function toggleMenu(postId) {
                const menu = document.getElementById('menu-' + postId);
                const allMenus = document.querySelectorAll('.dropdown-menu-custom');

                allMenus.forEach(m => {
                    if (m.id !== 'menu-' + postId) {
                        m.style.display = 'none';
                    }
                });

                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            }

            // Close menus when clicking outside
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.dropdown-menu-custom') && !e.target.closest('button[onclick^="toggleMenu"]')) {
                    document.querySelectorAll('.dropdown-menu-custom').forEach(menu => {
                        menu.style.display = 'none';
                    });
                }
            });

            // Image upload redirect to posting page
            document.addEventListener('DOMContentLoaded', function () {
                const photoBtn = document.getElementById('photoBtn');
                const imageInput = document.getElementById('post-image');

                if (!photoBtn || !imageInput) {
                    console.error("photoBtn or post-image NOT FOUND");
                    return;
                }

                photoBtn.addEventListener('click', function () {
                    imageInput.click();
                });

                imageInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    let form = new FormData();
                    form.append("image", file);

                    fetch("/set-upload-session", {
                        method: "POST",
                        body: form,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = "{{ route('posting.page') }}";
                            } else {
                                console.error("Failed to store session");
                            }
                        })
                        .catch(err => console.error(err));
                });
            });

            // Message request functionality
            function sendMessageRequest(userId, userName) {
                // Create modal for message composition
                const modal = document.createElement('div');
                modal.style.cssText = `
                                                                                                                                                        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                                                                                                                                                        background-color: rgba(0,0,0,0.8); display: flex; align-items: center;
                                                                                                                                                        justify-content: center; z-index: 9999; animation: fadeIn 0.2s ease;
                                                                                                                                                    `;

                modal.innerHTML = `
                                                                                                                                                        <div style="
                                                                                                                                                            background: linear-gradient(145deg, #2a2e38, #252933);
                                                                                                                                                            border-radius: 16px; padding: 28px; width: 90%; max-width: 420px;
                                                                                                                                                            position: relative; box-shadow: 0 20px 60px rgba(0,0,0,0.5);
                                                                                                                                                            animation: slideUp 0.3s ease;
                                                                                                                                                        ">
                                                                                                                                                            <button onclick="this.closest('div').parentElement.remove()"
                                                                                                                                                                style="
                                                                                                                                                                    position: absolute; right: 20px; top: 20px; background: none;
                                                                                                                                                                    border: none; color: #8b95a5; font-size: 24px; cursor: pointer;
                                                                                                                                                                ">×</button>
                                                                                                                                                            <h5 style="color: white; margin-bottom: 20px; font-size: 20px; font-weight: 600;">
                                                                                                                                                                Send Message to ${userName}
                                                                                                                                                            </h5>
                                                                                                                                                            <form id="messageRequestForm">
                                                                                                                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                                                                                                                <input type="hidden" name="receiver_id" value="${userId}">
                                                                                                                                                                <textarea name="message" placeholder="Write your message..."
                                                                                                                                                                    style="
                                                                                                                                                                        width: 100%; min-height: 100px; background-color: #1e2230;
                                                                                                                                                                        border: 1px solid #3a3f52; color: #fff; padding: 12px 16px;
                                                                                                                                                                        border-radius: 8px; font-size: 14px; resize: vertical;
                                                                                                                                                                        outline: none;
                                                                                                                                                                    " required></textarea>
                                                                                                                                                                <div style="display: flex; gap: 10px; margin-top: 20px;">
                                                                                                                                                                    <button type="button" onclick="this.closest('div').parentElement.remove()"
                                                                                                                                                                        style="
                                                                                                                                                                            flex: 1; background: #6b7280; color: white; border: none;
                                                                                                                                                                            padding: 12px; border-radius: 8px; cursor: pointer;
                                                                                                                                                                        ">Cancel</button>
                                                                                                                                                                    <button type="submit"
                                                                                                                                                                        style="
                                                                                                                                                                            flex: 1; background: #3b82f6; color: white; border: none;
                                                                                                                                                                            padding: 12px; border-radius: 8px; cursor: pointer;
                                                                                                                                                                        ">Send Request</button>
                                                                                                                                                                </div>
                                                                                                                                                            </form>
                                                                                                                                                        </div>
                                                                                                                                                    `;

                document.body.appendChild(modal);


            }
        </script>
    @endpush

@endsection