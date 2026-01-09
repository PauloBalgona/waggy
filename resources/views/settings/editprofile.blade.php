@extends('navbar.nav')
@section('title', 'Edit Profile - Waggy')
@section('body-class', 'bg-gray-900')

@section('content')

    <style>
        /* Responsive Design */
        @media (max-width: 768px) {
            .edit-container {
                padding: 12px 10px !important;
            }

            .d-flex.align-items-center.gap-3.mb-5 h1 {
                font-size: 20px !important;
            }

            #avatarPreview {
                width: 120px !important;
                height: 120px !important;
            }

            .btn-outline-light {
                font-size: 13px !important;
                padding: 8px 16px !important;
            }

            .form-group label {
                font-size: 14px !important;
            }

            .form-control {
                font-size: 14px !important;
                padding: 8px 10px !important;
            }

            .btn-primary {
                font-size: 14px !important;
                padding: 8px 20px !important;
            }
        }

        @media (max-width: 480px) {
            .edit-container {
                padding: 8px 8px !important;
            }

            .d-flex.align-items-center.gap-3.mb-5 {
                gap: 10px !important;
                margin-bottom: 20px !important;
            }

            .d-flex.align-items-center.gap-3.mb-5 h1 {
                font-size: 18px !important;
            }

            .d-flex.align-items-center.gap-3.mb-5 .bi-chevron-left {
                font-size: 20px !important;
            }

            #avatarPreview {
                width: 100px !important;
                height: 100px !important;
            }

            .btn-outline-light {
                font-size: 12px !important;
                padding: 6px 12px !important;
            }

            #fileInfo {
                font-size: 11px !important;
            }

            .form-group {
                margin-bottom: 14px !important;
            }

            .form-group label {
                font-size: 13px !important;
                margin-bottom: 6px !important;
            }

            .form-control {
                font-size: 13px !important;
                padding: 6px 8px !important;
            }

            .btn-primary {
                font-size: 13px !important;
                padding: 6px 16px !important;
                width: 100%;
            }

            textarea.form-control {
                min-height: 80px !important;
            }
        }
    </style>

    <div class="settings-page">

            <!-- Header -->
            <div class="d-flex align-items-center gap-3 mb-5">
                <a href="{{ route('account') }}" class="btn p-0 border-0 text-white d-flex align-items-center"
                    style="font-size: 25px;">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <h1 class="text-white fw-bold m-0" style="font-size: 25px;">Edit Profile</h1>
            </div>

        <!-- Full width container with proper spacing -->
        <div class="edit-container" style="max-width: 50%; padding: 5px 15px; position:relative; left:250px; bottom:70px">
            <!-- Form -->
            <form action="{{ route('editprofile.update') }}" method="POST" enctype="multipart/form-data" class="text-white">
                @csrf
                @method('PUT')

                {{-- Success / Error messages --}}
                @if(session('success'))
                <div id="profileSuccessAlert" class="mb-4 alert alert-success">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        var alert = document.getElementById('profileSuccessAlert');
                        if(alert) { alert.style.display = 'none'; }
                    }, 2500);
                </script>
                @endif

                @if($errors->any())
                <div class="mb-4 alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <p class="text-muted small">You can update your profile photo and pet details below.</p>

                {{-- Avatar --}}
                <div class="text-center mb-5 mt-4">
                    <div class="avatar-wrapper">
                        <img id="avatarPreview"
                            src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                            style="width: 140px; height: 140px; border-radius: 50%; object-fit: cover;">
                    </div>

                    <div class="mt-4">
                        <label class="btn btn-outline-light px-4 py-2" style="font-size: 15px;">
                            Change Photo
                            <input id="avatarInput" type="file" name="avatar" accept="image/png,image/jpeg" hidden>
                        </label>
                        <div id="fileInfo" class="mt-2 text-sm text-white-50" style="font-size:13px;">Allowed: JPG, PNG — Max 2MB</div>
                    </div>
                </div>

                {{-- Pet Name --}}
                <div class="mb-4">
                    <label class="form-label mb-2" style="font-size: 15px;">Pet Name</label>
                    <input type="text" class="form-control bg-dark text-white border-secondary" name="pet_name"
                        style="padding: 12px 16px; font-size: 15px;" value="{{ old('pet_name', auth()->user()->pet_name) }}">
                </div>

                {{-- Pet Breed --}}
                <div class="mb-4">
                    <label class="form-label mb-2" style="font-size: 15px;">Pet Breed</label>
                    <input type="text" class="form-control bg-dark text-white border-secondary" name="pet_breed"
                        style="padding: 12px 16px; font-size: 15px;" value="{{ old('pet_breed', auth()->user()->pet_breed) }}">
                </div>

                {{-- Pet Age --}}
                <div class="mb-4">
                    <label class="form-label mb-2" style="font-size: 15px;">Pet Age</label>
                    <input type="number" class="form-control bg-dark text-white border-secondary" name="pet_age" min="0"
                        style="padding: 12px 16px; font-size: 15px;" value="{{ old('pet_age', auth()->user()->pet_age) }}">
                </div>

                {{-- Pet Gender --}}
                <div class="mb-4">
                    <label class="form-label mb-2" style="font-size: 15px;">Pet Gender</label>
                    <select class="form-select bg-dark text-white border-secondary" name="pet_gender"
                        style="padding: 12px 16px; font-size: 15px;">
                        <option value="Male" {{ old('pet_gender', auth()->user()->pet_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('pet_gender', auth()->user()->pet_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('pet_gender', auth()->user()->pet_gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        <option value="N/A" {{ old('pet_gender', auth()->user()->pet_gender) == 'N/A' ? 'selected' : '' }}>N/A</option>
                    </select>
                </div>

                {{-- Pet Features --}}
                <div class="mb-4">
                    <label class="form-label mb-2" style="font-size: 15px;">Pet Features</label>
                    <textarea rows="4" class="form-control bg-dark text-white border-secondary" name="pet_features"
                        style="padding: 12px 16px; font-size: 15px;">{{ old('pet_features', auth()->user()->pet_features) }}</textarea>
                </div>

                {{-- Save (all fields) --}}
                <button id="saveBtn" class="btn btn-primary w-100 mt-5 py-3" style="font-size: 16px; font-weight: 600;">Save Changes</button>

            </form>

        </div>
    </div>
    <script>
        const avatarInput = document.getElementById('avatarInput');
        const fileInfo = document.getElementById('fileInfo');
        const saveBtn = document.getElementById('saveBtn');

        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            // Client-side quick validation
            const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowed.includes(file.type)) {
                fileInfo.textContent = '❌ Invalid file type. Allowed: JPG, PNG';
                avatarInput.value = '';
                return;
            }

            const maxBytes = 2 * 1024 * 1024; // 2MB
            if (file.size > maxBytes) {
                fileInfo.textContent = '❌ File too large. Max 2 MB';
                avatarInput.value = '';
                return;
            }

            let imgPreview = document.querySelector('#avatarPreview');
            if (!imgPreview) {
                imgPreview = document.querySelector('.avatar-wrapper img');
                imgPreview.id = "avatarPreview";
            }
            imgPreview.src = URL.createObjectURL(file);
            fileInfo.textContent = `Selected: ${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`;
        });

        // Prevent submit if selected file invalid (extra guard)
        document.querySelector('form[action="{{ route('editprofile.update') }}"]').addEventListener('submit', function(e) {
            if (avatarInput.files.length > 0) {
                const f = avatarInput.files[0];
                if (f.size > 2 * 1024 * 1024) {
                    e.preventDefault();
                    alert('Avatar file is too large. Maximum allowed is 2 MB.');
                }
            }
        });
    </script>

@endsection