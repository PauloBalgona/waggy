@extends('navbar.nav')
@section('title', 'Edit Post - Waggy')
@section('content')

    <style>
        .main-wrapper {
            padding: 0 !important;
        }

        .edit-container {
            background-color: #1a1f2e;
            min-height: 100vh;
            padding: 40px 20px;
            width: 100%;
            margin: 0;
        }

        .edit-form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #1c2230;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: #e5e7eb;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            background-color: #2a3142;
            border: 1px solid #3d4557;
            border-radius: 6px;
            padding: 12px;
            color: #fff;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control::placeholder {
            color: #8b95a5;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background-color: #2a3142;
            color: #fff;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        .form-select {
            width: 100%;
            background-color: #2a3142;
            border: 1px solid #3d4557;
            border-radius: 6px;
            padding: 12px;
            color: #fff;
            font-size: 14px;
        }

        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .form-select option {
            background-color: #1c2230;
            color: #fff;
        }

        .photo-preview {
            margin-top: 12px;
            border-radius: 8px;
            overflow: hidden;
            max-width: 100%;
            max-height: 300px;
        }

        .photo-preview img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .btn-submit {
            width: 100%;
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #2563eb;
        }

        .btn-cancel {
            width: 100%;
            background-color: #6b7280;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            text-decoration: none;
            text-align: center;
            display: block;
            transition: background-color 0.2s;
        }

        .btn-cancel:hover {
            background-color: #4b5563;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .edit-form {
                padding: 20px;
                margin: 0 auto;
            }

            .edit-title {
                font-size: 22px;
                margin-bottom: 20px;
            }

            .form-group {
                margin-bottom: 16px;
            }

            .form-label {
                font-size: 13px;
                margin-bottom: 6px;
            }

            .form-control,
            .form-select {
                font-size: 13px;
                padding: 10px;
            }

            textarea.form-control {
                min-height: 100px;
            }

            .form-row {
                gap: 12px;
            }

            .btn {
                font-size: 13px;
                padding: 8px 16px;
            }

            .photo-preview img {
                max-height: 200px !important;
            }
        }

        @media (max-width: 480px) {
            .edit-container {
                padding: 16px 8px;
            }

            .edit-form {
                padding: 16px;
                border-radius: 8px;
                max-width: 100%;
            }

            .edit-title {
                font-size: 18px;
                margin-bottom: 16px;
            }

            .form-group {
                margin-bottom: 12px;
            }

            .form-label {
                font-size: 12px;
                margin-bottom: 5px;
            }

            .form-control,
            .form-select {
                font-size: 12px;
                padding: 8px;
                border-radius: 4px;
            }

            textarea.form-control {
                min-height: 80px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .btn-primary {
                width: 100%;
                font-size: 12px;
                padding: 8px 12px;
            }

            .btn-secondary {
                width: 100%;
                font-size: 12px;
                padding: 8px 12px;
            }

            .photo-preview {
                margin-bottom: 8px;
            }

            .photo-preview img {
                max-width: 100%;
                max-height: 150px;
                border-radius: 4px;
            }

            small {
                font-size: 10px !important;
            }

            .error-message {
                font-size: 11px;
            }
        }

        .edit-title {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }

        .error-message {
            color: #fca5a5;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>

    <div class="edit-container">
        <div class="edit-form">
            <h1 class="edit-title">Edit Post</h1>

            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Content -->
                <div class="form-group">
                    <label class="form-label">What's on your mind?</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" name="content"
                        placeholder="Share something...">{{ old('content', $post->message) }}</textarea>
                    @error('content')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Photo -->
                <div class="form-group">
                    <label class="form-label">Photo</label>
                    @if($post->photo)
                        <div class="photo-preview">
                            <img src="{{ asset('storage/' . $post->photo) }}" alt="Current post photo">
                        </div>
                        <small style="color: #9ca3af; display: block; margin-top: 8px;">Current photo (upload a new one to
                            replace)</small>
                    @endif
                    <input type="file" class="form-control @error('photoUpload') is-invalid @enderror" name="photoUpload"
                        accept="image/*">
                    @error('photoUpload')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Age and Breed -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" class="form-control" name="age" min="1" max="5"
                            value="{{ old('age', $post->age) }}" placeholder="e.g., 3">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Breed</label>
                        <input type="text" class="form-control" name="breed" value="{{ old('breed', $post->breed) }}"
                            placeholder="e.g., Labrador">
                    </div>
                </div>

                <!-- City and Province -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="city" value="{{ old('city', $post->city) }}"
                            placeholder="e.g., Manila">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Province</label>
                        <input type="text" class="form-control" name="province"
                            value="{{ old('province', $post->province) }}" placeholder="e.g., Metro Manila">
                    </div>
                </div>

                <!-- Interest -->
                <div class="form-group">
                    <label class="form-label">Interest</label>
                    <select class="form-select" name="interest">
                        <option value="">Select Interest</option>
                        <option value="Breeding" @if(old('interest', $post->interest) === 'Breeding') selected @endif>Breeding
                        </option>
                        <option value="Playdate" @if(old('interest', $post->interest) === 'Playdate') selected @endif>Playdate
                        </option>
                    </select>
                </div>

                <!-- Audience -->
                <div class="form-group">
                    <label class="form-label">Who can see this?</label>
                    <select class="form-select" name="audience">
                        <option value="public" @if(old('audience', $post->audience) === 'public') selected @endif>Public
                        </option>
                        <option value="friends" @if(old('audience', $post->audience) === 'friends') selected @endif>Friends
                            Only</option>
                    </select>
                </div>

                <!-- Buttons -->
                <button type="submit" class="btn-submit">Update Post</button>
                <a href="{{ route('home') }}" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>

@endsection