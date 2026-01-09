@extends('navbar.nav')

@section('title', 'Profile')
@section('body-class', 'bg-gray-900 text-white')

@section('content')

    <style>
    /* ================= MODAL ================= */
    .profile-page .modern-modal { background: #1f2430; border-radius: 18px; border: 1px solid #2f3545; box-shadow: 0 20px 50px rgba(0, 0, 0, .6); color: #fff; }
    .profile-page .modern-header { border-bottom: 1px solid #2f3545; padding: 16px 20px; }
    .profile-page .modern-header h5 { margin: 0; font-weight: 600; }
    .profile-page .avatar-wrapper { width: 110px; height: 110px; border-radius: 50%; overflow: hidden; margin: auto; position: relative; border: 3px solid #3b82f6; }
    .profile-page .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .profile-page .avatar-edit { position: absolute; bottom: 6px; right: 6px; background: #3b82f6; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; box-shadow: 0 4px 12px rgba(0, 0, 0, .4); }
    .profile-page .modern-input { background: #2a2f3d; border: none; color: #fff; border-radius: 5px; padding: 10px 14px; }
    .profile-page .modern-input:focus { background: #2a2f3d; color: #fff; box-shadow: 0 0 0 2px rgba(59, 130, 246, .4); outline: none; }
    .profile-page .modern-save-btn { width: 100%; padding: 12px; border-radius:5px; border: none; font-weight: 600; background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; transition: .2s; }
    .profile-page .modern-save-btn:hover { opacity: .9; }
    .profile-page .modern-save-btn:active { transform: scale(.97); }
    .profile-page .nav-tabs .nav-link.active { border: none !important; color: white !important; }
    .profile-page .nav-tabs .nav-link { transition: 0.3s ease; border: none !important; }
    .profile-page .nav-tabs .nav-link:hover { color: white !important; background: rgba(255, 255, 255, 0.08) !important; border-radius: 6px; }

    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-page { padding: 0 12px; margin-top: 20px; }
        .profile-page .d-flex.align-items-center.gap-4.mb-4 { flex-direction: column; text-align: center; gap: 16px !important; }
        .profile-page .d-flex.align-items-center.gap-4.mb-4 img { width: 100px !important; height: 100px !important; }
        .profile-page .profile-info { width: 100%; }
        .profile-page .nav-tabs { flex-wrap: wrap; }
        .profile-page .nav-tabs .nav-link { padding: 12px 16px !important; font-size: 13px; }
        .profile-page .nav-tabs .nav-link span { display: none; }
        .profile-page .d-flex.justify-content-between.align-items-center { flex-direction: column; align-items: flex-start !important; }
        .profile-page button[data-bs-target="#editProfileModal"] { position: relative; left:220px; bottom: 46px; width: 36%; font-size: 12px; }
        .profile-page #editProfileModal .modal-dialog { margin-top: 60px; position: relative; right: 8px; }
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

                {{-- SUCCESS MESSAGE REMOVED --}}

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
                @elseif(auth()->id() !== $user->id)
                    <div id="friend-action-area">
                        @if($friendRequest && $friendRequest->status == 'pending')
                            <button id="cancel-request-btn" class="px-3 py-2 rounded text-white position-relative" style="background-color: red;">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="cancel-spinner" role="status" aria-hidden="true"></span>
                                <i class="bi bi-x-circle"></i> Cancel Request
                            </button>
                        @elseif($friendRequest && $friendRequest->status == 'accepted')
                            <button id="unfriend-btn" class="px-3 py-2 rounded text-white position-relative" style="background-color: red;">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="unfriend-spinner" role="status" aria-hidden="true"></span>
                                <i class="bi bi-person-dash"></i> Unfriend
                            </button>
                        @else
                            <button id="add-friend-btn" class="px-3 py-2 rounded text-white position-relative" style="background-color: blue;">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="add-spinner" role="status" aria-hidden="true"></span>
                                <i class="bi bi-person-plus"></i> Add Friend
                            </button>
                        @endif
                    </div>
                @endif

            </div>

            {{-- TAB CONTENT --}}

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const friendActionArea = document.getElementById('friend-action-area');
                if (!friendActionArea) return;
                const userId = {{ $user->id }};

                function setButton(html) {
                    friendActionArea.innerHTML = html;
                    attachHandlers();
                }

                function attachHandlers() {
                    // Add Friend
                    const addBtn = document.getElementById('add-friend-btn');
                    const addSpinner = document.getElementById('add-spinner');
                    if (addBtn) {
                        addBtn.onclick = function() {
                            addBtn.disabled = true;
                            if (addSpinner) addSpinner.classList.remove('d-none');
                            // Show spinner instantly
                            setTimeout(() => {
                                fetch("{{ route('friend.request.send') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ receiver_id: userId })
                                })
                                .then(async response => {
                                    addBtn.disabled = false;
                                    if (addSpinner) addSpinner.classList.add('d-none');
                                    if (!response.ok) {
                                        let msg = 'Failed to send friend request.';
                                        try {
                                            const data = await response.json();
                                            if (data && data.error) msg = data.error;
                                            else if (data && data.message) msg = data.message;
                                        } catch (e) {
                                            try { msg = await response.text(); } catch (e2) {}
                                        }
                                        alert(msg);
                                        return;
                                    }
                                    setButton(`<button id='cancel-request-btn' class='px-3 py-2 rounded text-white position-relative' style='background-color: red;'><span class='spinner-border spinner-border-sm me-2 d-none' id='cancel-spinner' role='status' aria-hidden='true'></span><i class='bi bi-x-circle'></i> Cancel Request</button>`);
                                })
                                .catch(async (err) => {
                                    addBtn.disabled = false;
                                    if (addSpinner) addSpinner.classList.add('d-none');
                                    let msg = err && err.message ? err.message : 'Failed to send friend request.';
                                    alert(msg);
                                });
                            }, 10); // minimal delay to ensure spinner shows
                        };
                    }
                    // Cancel Request
                    const cancelBtn = document.getElementById('cancel-request-btn');
                    const cancelSpinner = document.getElementById('cancel-spinner');
                    if (cancelBtn) {
                        cancelBtn.onclick = function() {
                            cancelBtn.disabled = true;
                            if (cancelSpinner) cancelSpinner.classList.remove('d-none');
                            fetch(`/friend-request/cancel/${userId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(async response => {
                                cancelBtn.disabled = false;
                                if (cancelSpinner) cancelSpinner.classList.add('d-none');
                                console.log('Cancel Friend response:', response);
                                if (!response.ok) {
                                    let msg = 'Failed to cancel request.';
                                    try {
                                        const data = await response.json();
                                        if (data && data.error) msg = data.error;
                                        else if (data && data.message) msg = data.message;
                                    } catch (e) {
                                        try { msg = await response.text(); } catch (e2) {}
                                    }
                                    alert(msg);
                                    return;
                                }
                                setButton(`<button id='add-friend-btn' class='px-3 py-2 rounded text-white position-relative' style='background-color: blue;'><span class='spinner-border spinner-border-sm me-2 d-none' id='add-spinner' role='status' aria-hidden='true'></span><i class='bi bi-person-plus'></i> Add Friend</button>`);
                            })
                            .catch(async (err) => {
                                cancelBtn.disabled = false;
                                if (cancelSpinner) cancelSpinner.classList.add('d-none');
                                let msg = err && err.message ? err.message : 'Failed to cancel request.';
                                alert(msg);
                            });
                        };
                    }
                    // Unfriend
                    const unfriendBtn = document.getElementById('unfriend-btn');
                    const unfriendSpinner = document.getElementById('unfriend-spinner');
                    if (unfriendBtn) {
                        unfriendBtn.onclick = function() {
                            unfriendBtn.disabled = true;
                            if (unfriendSpinner) unfriendSpinner.classList.remove('d-none');
                            fetch(`/friend/unfriend/${userId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(async response => {
                                unfriendBtn.disabled = false;
                                if (unfriendSpinner) unfriendSpinner.classList.add('d-none');
                                if (!response.ok) throw new Error(await response.text());
                                setButton(`<button id='add-friend-btn' class='px-3 py-2 rounded text-white position-relative' style='background-color: blue;'><span class='spinner-border spinner-border-sm me-2 d-none' id='add-spinner' role='status' aria-hidden='true'></span><i class='bi bi-person-plus'></i> Add Friend</button>`);
                            })
                            .catch(() => { unfriendBtn.disabled = false; if (unfriendSpinner) unfriendSpinner.classList.add('d-none'); alert('Failed to unfriend.'); });
                        };
                    }
                }
                // Initial attach
                attachHandlers();
            });
            </script>
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
                                        <span class="text-white fw-bold post-likes-count" data-post-id="{{ $post->id }}" style="font-size: 12px;">{{ $post->likes_count }}</span>
                                        {{-- JS polling code removed from HTML output. Place this in a <script> tag at the bottom of the page if needed. --}}
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
                        @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center mt-3">
                                {{ $posts->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
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
                        @if($dogPhotos instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center mt-3">
                                {{ $dogPhotos->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
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
                        @if($friends instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center mt-3">
                                {{ $friends->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
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
                        @if($likedPosts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center mt-3">
                                {{ $likedPosts->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                </div>

            </div>

        </div>

        {{-- EDIT PROFILE MODAL --}}
        @if(auth()->id() === $user->id)
            <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content modern-modal">

            {{-- HEADER --}}
            <div class="modal-header modern-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">
                <form action="{{ route('editprofile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- AVATAR --}}
                    <div class="text-center mb-4">
                        <div class="avatar-wrapper">
                            <img id="avatarPreview"
                                 src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}">
                            <label for="profileAvatarInput" class="avatar-edit">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                        </div>

                        <input id="profileAvatarInput" type="file" name="avatar" hidden
                               accept="image/png,image/jpeg">

                        <small class="text-muted d-block mt-2">
                            JPG / PNG • Max 2MB
                        </small>
                    </div>

                    {{-- INPUTS --}}
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Pet Name</label>
                            <input type="text" class="form-control modern-input"
                                   name="pet_name"
                                   value="{{ old('pet_name', auth()->user()->pet_name) }}"
                                   placeholder="Enter pet name">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Pet Breed</label>
                            <input type="text" class="form-control modern-input"
                                   name="pet_breed"
                                   value="{{ old('pet_breed', auth()->user()->pet_breed) }}"
                                   placeholder="Enter pet breed">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Pet Age</label>
                            <input type="number" class="form-control modern-input"
                                   name="pet_age" min="0"
                                   value="{{ old('pet_age', auth()->user()->pet_age) }}">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Pet Gender</label>
                            <select class="form-select modern-input" name="pet_gender">
                                <option value="Male" {{ auth()->user()->pet_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ auth()->user()->pet_gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ auth()->user()->pet_gender == 'Other' ? 'selected' : '' }}>Other</option>
                                <option value="N/A" {{ auth()->user()->pet_gender == 'N/A' ? 'selected' : '' }}>N/A</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Pet Features</label>
                            <textarea rows="3" class="form-control modern-input"
                                      name="pet_features"
                                      placeholder="Describe your pet...">{{ old('pet_features', auth()->user()->pet_features) }}</textarea>
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <button type="submit" class="btn modern-save-btn mt-4">
                        Save Changes
                    </button>
                </form>
            </div>
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