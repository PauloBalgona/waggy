@extends('navbar.nav1')

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
    </style>

    <div class="profile-page">

        <div style="max-width: 1200px;">

            {{-- LARGE PROFILE HEADER --}}
            <div class="d-flex align-items-center gap-4 mb-4">
                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
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

                {{-- ADD FRIEND BUTTON (RIGHT SIDE) --}}
                @if(auth()->id() !== $user->id)
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection