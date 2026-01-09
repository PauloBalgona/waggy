@extends('navbar.nav')

@section('title', 'Profile')
@section('body-class', 'bg-gray-900 text-white')

@section('content')

    <style>
        .nav-tabs .nav-link.active {
            border: none !important;
            color: white !important;
        }

        .nav-tabs .nav-link {
            transition: 0.3s ease;
            border: none !important;
        }

        .nav-tabs .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.08) !important;
            border-radius: 6px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-page {
                padding: 0 12px;
            }

            .d-flex.align-items-center.gap-4.mb-4 {
                flex-direction: column;
                text-align: center;
                gap: 16px !important;
            }

            .d-flex.align-items-center.gap-4.mb-4 img {
                width: 100px !important;
                height: 100px !important;
            }

            .profile-info {
                width: 100%;
            }

            .nav-tabs {
                flex-wrap: wrap;
            }

            .nav-tabs .nav-link {
                padding: 12px 16px !important;
                font-size: 13px;
            }

            .nav-tabs .nav-link span {
                display: none;
            }

            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                align-items: flex-start !important;
            }
            button[data-bs-target="#editProfileModal"] {
        position: relative;
        left:220px;
        bottom: 46px;
        width: 36%;
         font-size: 12px;
    }
    .profile-page {
        margin-top: 20px;   
    }
     #editProfileModal .modal-dialog {
        margin-top: 60px;
        position: relative;
        right: 8px;
    }

        }
    </style>

    <div class="profile-page">

        <div style="max-width: 1200px;">

            {{-- LARGE PROFILE HEADER --}}
            <div class="d-flex align-items-center gap-4 mb-4">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/usericon.png') }}"
                    alt="{{ $user->name }}" class="rounded-circle"
                    style="width: 120px; height: 120px; border: 4px solid #4a5568; object-fit: cover;">

                <div class="profile-info">
                    <h6 class="text-white fw-semibold mb-2 fs-5">
                        {{ $user->pet_name ?? 'Pet Name' }}
                    </h6>
                    <small class="text-secondary fs-6">
                        {{ $user->pet_breed ?? 'Breed not set' }}
                    </small>
                </div>
            </div>

            {{-- TABS + ADD FRIEND BUTTON (RIGHT SIDE) --}}
            <div style="border-bottom: 2px solid #4a5568;" class="mb-4 d-flex justify-content-between align-items-center">

                {{-- ERROR MESSAGE --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <ul class="nav nav-tabs border-0 gap-1">

                    {{-- MY POST --}}
                    <li class="nav-item">
                        <button class="nav-link border-0 px-4 py-3" style="color:#a0aec0; background:none;"
                            data-bs-toggle="tab" data-bs-target="#content-my-post">
                            <i class="bi bi-grid-3x3" style="font-size:20px;"></i>
                            <span>My Post</span>
                        </button>
                    </li>

                    {{-- MY DOG --}}
                    <button class="nav-link border-0 px-4 py-3" style="color:#a0aec0; background:none;" data-bs-toggle="tab"
                        data-bs-target="#content-my-dog">
                        <i class="bi bi-person-circle" style="font-size:20px;"></i>
                        <span>My Dog</span>
                    </button>

                    {{-- CONNECTION --}}
                    <li class="nav-item">
                        <button class="nav-link border-0 px-4 py-3" style="color:#a0aec0; background:none;"
                            data-bs-toggle="tab" data-bs-target="#content-connection">
                            <i class="bi bi-people" style="font-size:20px;"></i>
                            <span>Connection</span>
                        </button>
                    </li>

                    {{-- LIKES --}}
                    <li class="nav-item">
                        <button class="nav-link border-0 px-4 py-3" style="color:#a0aec0; background:none;"
                            data-bs-toggle="tab" data-bs-target="#content-likes">
                            <i class="bi bi-heart" style="font-size:20px;"></i>
                            <span>Likes</span>
                        </button>
                    </li>

                </ul>

                {{-- EDIT PROFILE BUTTON (FOR OWN PROFILE) --}}
                @if(auth()->id() === $user->id)
                    <button type="button" class="px-4 py-2 rounded text-white"
                        style="background-color: #4a90e2; border: none; font-weight: 600;" data-bs-toggle="modal"
                        data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </button>
                    {{-- ADD FRIEND BUTTON (FOR OTHER PROFILES) --}}
                @elseif(auth()->id() !== $user->id)
                    @if($friendRequest && $friendRequest->status == 'pending')
                        <form action="{{ route('friend.request.cancel', $user->id) }}" method="POST">
                            @csrf
                            <button class="px-3 py-2 rounded text-white" style="background-color: red;">
                                <i class="bi bi-x-circle"></i> Cancel Request
                            </button>
                        </form>
                    @elseif($friendRequest && $friendRequest->status == 'accepted')
                        <form action="{{ route('friend.unfriend', $user->id) }}" method="POST">
                            @csrf
                            <button class="px-3 py-2 rounded text-white" style="background-color: red;">
                                <i class="bi bi-person-dash"></i> Unfriend
                            </button>
                        </form>
                    @else
                        <form action="{{ route('friend.request.send') }}" method="POST">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                            <button class="px-3 py-2 rounded text-white" style="background-color: blue;">
                                <i class="bi bi-person-plus"></i> Add Friend
                            </button>
                        </form>
                    @endif
                @endif

            </div>

            {{-- TAB CONTENT --}}
            <div class="tab-content">

                {{-- MY POSTS --}}
                <div class="tab-pane fade" id="content-my-post">
                    <div class="row g-1">

                        @forelse ($posts as $post)
                            <div class="col-lg-4 col-md-6 col-4">
                                <div class="position-relative overflow-hidden" style="aspect-ratio:1/1; cursor:pointer;">
                                    <img src="{{ $post->photo ? asset('storage/' . $post->photo) : 'https://placehold.co/400x400?text=No+Photo' }}"
                                        class="w-100 h-100" style="object-fit:cover;">
                                    {{-- Like Count Overlay --}}
                                    <div
                                        class="position-absolute bottom-0 end-0 m-2 d-flex align-items-center gap-1 bg-dark bg-opacity-75 rounded-pill px-2 py-1">
                                        <i class="bi bi-heart-fill text-danger" style="font-size: 12px;"></i>
                                        <span class="text-white fw-bold"
                                            style="font-size: 12px;">{{ $post->likes_count }}</span>
                                    </div>
                                    {{-- Like Button for non-own profiles --}}
                                    @if(!$isOwnProfile)
                                        <form action="{{ route('posts.like', $post->id) }}" method="POST"
                                            class="position-absolute top-0 end-0 m-2">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm p-1 rounded-circle {{ $post->likes->where('user_id', auth()->id())->count() > 0 ? 'text-primary' : 'text-secondary' }}"
                                                style="background: rgba(0,0,0,0.5); border: none;">
                                                <i class="bi bi-heart" style="font-size: 16px;"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-secondary">No posts yet.</p>
                        @endforelse

                    </div>
                </div>

                {{-- MY DOG --}}
                <div class="tab-pane fade show active" id="content-my-dog">
                    <div class="row g-1">

                        @forelse ($dogPhotos as $photo)
                            <div class="col-lg-4 col-md-6 col-4">
                                <div class="position-relative overflow-hidden" style="aspect-ratio:1/1;">
                                    <img src="{{ asset('storage/' . $photo->image_path) }}" class="w-100 h-100"
                                        style="object-fit:cover;">
                                </div>
                            </div>
                        @empty
                            <p class="text-secondary">No dog photos uploaded yet.</p>
                        @endforelse

                    </div>
                </div>

                {{-- CONNECTION --}}
                <div class="tab-pane fade" id="content-connection">
                    <div class="row g-1">
                        @forelse ($friends as $friend)
                            <div class="col-lg-4 col-md-6 col-4">
                                <div class="position-relative overflow-hidden" style="aspect-ratio:1/1; cursor:pointer;"
                                    onclick="window.location.href='{{ route('profile.show', $friend->id) }}'">
                                    <img src="{{ $friend->avatar ? asset('storage/' . $friend->avatar) : asset('assets/usericon.png') }}"
                                        class="w-100 h-100" style="object-fit:cover;">
                                    <div
                                        class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white text-center py-1">
                                        <small>{{ $friend->pet_name ?? 'Friend' }}</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-secondary">No connections yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- LIKES --}}
                <div class="tab-pane fade" id="content-likes">
                    <div class="row g-1">
                        @forelse ($likedPosts as $post)
                            <div class="col-lg-4 col-md-6 col-4">
                                <div class="position-relative overflow-hidden" style="aspect-ratio:1/1; cursor:pointer;"
                                    onclick="window.location.href='{{ route('profile.show', $post->user->id) }}'">
                                    <img src="{{ $post->photo ? asset('storage/' . $post->photo) : 'https://placehold.co/400x400?text=No+Photo' }}"
                                        class="w-100 h-100" style="object-fit:cover;">
                                    {{-- Like Count Overlay --}}
                                    <div
                                        class="position-absolute bottom-0 end-0 m-2 d-flex align-items-center gap-1 bg-dark bg-opacity-75 rounded-pill px-2 py-1">
                                        <i class="bi bi-heart-fill text-danger" style="font-size: 12px;"></i>
                                        <span class="text-white fw-bold"
                                            style="font-size: 12px;">{{ $post->likes_count }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-secondary">No liked posts yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

        {{-- EDIT PROFILE MODAL --}}
        @if(auth()->id() === $user->id)
            <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 500px; width: 100%;">
                    <div class="modal-content bg-dark border-secondary">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title text-white" id="editProfileModalLabel">Edit Profile</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('editprofile.update') }}" method="POST" enctype="multipart/form-data"
                                class="text-white">
                                @csrf
                                @method('PUT')

                                {{-- Avatar --}}
                                <div class="text-center mb-4">
                                    <img id="avatarPreview"
                                        src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                                        style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #4a5568;">
                                    <div class="mt-3">
                                        <label class="btn btn-outline-light btn-sm">
                                            <i class="bi bi-camera"></i> Change Photo
                                            <input id="profileAvatarInput" type="file" name="avatar" hidden
                                                accept="image/png,image/jpeg">
                                        </label>
                                        <div id="profileFileInfo" class="mt-2 small text-secondary">Allowed: JPG, PNG — Max 2MB
                                        </div>
                                    </div>
                                </div>

                                {{-- Pet fields are shown but not editable here (avatar-only modal). Remove name attributes so
                                they are not submitted. --}}
                                <div class="mb-3">
                                    <label class="form-label">Pet Name</label>
                                    <input type="text" class="form-control bg-secondary text-white border-0"
                                        value="{{ auth()->user()->pet_name }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pet Breed</label>
                                    <input type="text" class="form-control bg-secondary text-white border-0"
                                        value="{{ auth()->user()->pet_breed }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pet Age</label>
                                    <input type="number" class="form-control bg-secondary text-white border-0"
                                        value="{{ auth()->user()->pet_age }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pet Gender</label>
                                    <input type="text" class="form-control bg-secondary text-white border-0"
                                        value="{{ auth()->user()->pet_gender ?? 'N/A' }}" readonly>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Pet Features</label>
                                    <textarea rows="3" class="form-control bg-secondary text-white border-0"
                                        readonly>{{ auth()->user()->pet_features }}</textarea>
                                </div>

                                <button id="profileSaveBtn" type="submit" class="btn btn-primary w-100 fw-bold">Save
                                    Photo</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                const profileAvatarInput = document.getElementById('profileAvatarInput');
                const profileFileInfo = document.getElementById('profileFileInfo');
                const profileAvatarPreview = document.querySelector('#editProfileModal #avatarPreview');

                if (profileAvatarInput) {
                    profileAvatarInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        // client-side validation
                        const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!allowed.includes(file.type)) {
                            profileFileInfo.textContent = '❌ Invalid file type. Allowed: JPG, PNG';
                            profileAvatarInput.value = '';
                            return;
                        }

                        const maxBytes = 2 * 1024 * 1024; // 2MB
                        if (file.size > maxBytes) {
                            profileFileInfo.textContent = '❌ File too large. Max 2 MB';
                            profileAvatarInput.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            if (profileAvatarPreview) profileAvatarPreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                        profileFileInfo.textContent = `Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    });
                }

                // Prevent submit if selected file invalid (extra guard)
                const profileForm = document.querySelector('#editProfileModal form[action="{{ route('editprofile.update') }}"]');
                if (profileForm) {
                    profileForm.addEventListener('submit', function (e) {
                        if (profileAvatarInput && profileAvatarInput.files.length > 0) {
                            const f = profileAvatarInput.files[0];
                            if (f.size > 2 * 1024 * 1024) {
                                e.preventDefault();
                                alert('Avatar file is too large. Maximum allowed is 2 MB.');
                            }
                        }
                    });
                }
            </script>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection