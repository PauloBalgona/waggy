@extends('navbar.nav1')
@section('title', 'Home - Waggy')
@section('body-class', 'bg-gray-900')

@section('content')


    <style>
    #filter-section .btn-link,
    #filter-section .btn-link:focus,
    #filter-section .btn-link:active,
    #filter-section .btn-link:focus-visible {
    background-color: transparent !important;
    box-shadow: none !important;
    outline: none !important;
    border: none !important;
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

        .dropdown-menu-custom {
            background: #252933 !important;
            border: 1px solid #3d4557 !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4) !important;
            padding: 6px 0 !important;
            min-width: 140px !important;
            z-index: 1000 !important;
        }

        .dropdown-item-custom {
            display: block !important;
            width: 100% !important;
            padding: 10px 16px !important;
            color: #e5e7eb !important;
            background-color: transparent !important;
            border: none !important;
            text-align: left !important;
            cursor: pointer !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            transition: all 0.15s ease !important;
        }

        .dropdown-item-custom:hover {
            background-color: #3d4557 !important;
            color: #ffffff !important;
        }

        .dropdown-item-custom:active {
            background-color: #2a3142 !important;
        }

        .dropdown-item-custom.text-danger {
            color: #ef4444 !important;
        }

        .dropdown-item-custom.text-danger:hover {
            color: #fca5a5 !important;
            background-color: rgba(239, 68, 68, 0.1) !important;
        }

        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        *::-webkit-scrollbar {
            display: none;
        }
        
        /* MOBILE ONLY FIX */
        @media (max-width: 768px) {
            /* USER ICON (POSTING + POSTS) */
        section.post img.rounded-circle,
        .post .rounded-circle {
            width: 36px !important;
            height: 36px !important;
            object-fit: cover !important;
            margin-top: 0 !important;
        }

        /* ADD THIS - FORCE ROUND AVATAR */
        section.post img.rounded-circle,
        .post .rounded-circle,
        .post img[src*="avatar"],
        .post img[src*="usericon"] {
            border-radius: 50% !important;
            display: block !important;
            position: relative !important;
            left: 7px !important;
        }

            

            /* POST CONTAINER FULL WIDTH */
            .post-container {
                max-width: 100% !important;
                margin-left: 0 !important;
                padding: 0 !important;
            }

            /* POST CARD */
            .post {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 0 12px 0 !important;
                border-radius: 0 !important;
            }

            /* IMAGE EDGE TO EDGE */
            .post > div[style*="background-color:#1B1E25"] {
                margin-left: -16px !important;
                margin-right: -16px !important;
                border-radius: 0 !important;
            }

            .post img {
                width: 100% !important;
                height: auto !important;
                display: block !important;
            }

            /* ACTION BUTTONS CENTERED */
            .post .border-top {
                justify-content: center !important;
                gap: 40px !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .post .border-top form,
            .post .border-top a {
                flex: 0 !important;
            }

            .post > div[style*="max-height"] {
                margin-left: -8px !important;
                margin-right: -8px !important;
                width: calc(100% + 16px) !important;
                border-radius: 0 !important;
            }

            .post > div img {
                width: 100% !important;
                height: auto !important;
                object-fit: cover !important;
                border-radius: 0 !important;
                display: block !important;
            }

            /* USER ICON (POSTING + POSTS) */
            section.post img.rounded-circle,
            .post .rounded-circle {
                width: 36px !important;
                height: 36px !important;
                object-fit: cover !important;
                margin-top: 0 !important;
            }

            /* ALIGN ICON + INPUT */
            section.post .d-flex.align-items-start {
                align-items: center !important;
                gap: 10px !important;
            }

            /* WHAT'S ON YOUR MIND */
            section.post div[onclick] {
                min-height: 38px !important;
                padding: 8px 12px !important;
                font-size: 12.5px !important;
                border-radius: 10px !important;
                line-height: 1.3 !important;
            }

            /* IMAGE BUTTON */
            #photoBtn {
                width: 40px !important;
                height: 38px !important;
                border-radius: 10px !important;
            }

            #photoBtn i {
                font-size: 18px !important;
            }

            /* FILTER SECTION */
            #filter-section {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 10px 12px !important;
                padding: 8px 0 4px !important;
            }

            #filter-section button,
            #filter-section a {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 6px !important;
                font-size: 12px !important;
                padding: 6px 8px !important;
                border-radius: 10px !important;
            }

            #filter-section i {
                font-size: 16px !important;
            }
            
            #filter-section a {
                justify-content: flex-start !important;
                padding-left: 8px !important;
            }
            
            /* LIKE + COMMENT WRAPPER */
            .post .d-flex.justify-content-around {
                justify-content: space-evenly !important;
                padding: 6px 0 !important;
            }

            /* LIKE / COMMENT BUTTON */
            .post .btn-link {
                display: flex !important;
                align-items: center !important;
                gap: 6px !important;
                font-size: 12px !important;
                padding: 4px 6px !important;
            }

            .post .btn-link i {
                font-size: 16px !important;
                line-height: 1 !important;
                display: flex;
                align-items: center;
            }

            .post .btn-link span {
                line-height: 1 !important;
                display: flex;
                align-items: center;
            }
            
            .post .bi-heart,
            .post .bi-chat-dots {
                font-size: 16px;
                position: relative;
                top: 5px;
                line-height: 1;
                vertical-align: middle;
                display: inline-block;
            }
            
            .post .btn-link {
                text-decoration: none !important;
                border: none !important;
                box-shadow: none !important;
                outline: none !important;
            }

            .post .btn-link:focus,
            .post .btn-link:active,
            .post .btn-link:focus-visible {
                outline: none !important;
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
    
    <div class="min-h-screen flex">
        <div class="post-container" style="max-width:70%; margin-left:130px; display:flex; flex-direction:column; gap:5px;">

            <!-- POST INPUT SECTION -->
            <section class="post p-4 mt-0"
                style="background-color:#292D37; border-radius:5px; position: relative; margin-bottom: 10px;">

                <div class="d-flex align-items-start mb-3">
                    <img
                        src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                        alt="Profile"
                        class="rounded-circle me-3 mt-1"
                        style="width:50px; height:50px; object-fit:cover; background:#333; cursor:pointer;"
                        onclick="window.location.href='{{ route('profile.show', auth()->user()->id) }}'">

                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="w-100">
                        @csrf

                        <div class="d-flex align-items-center w-100 gap-3">
                            <!-- POSTING PAGE -->
                            <div onclick="window.location.href='{{ route('posting.page') }}'"
                                style="flex:1; padding:13px 14px; background:#1B1E25; border-radius:12px; color:#adb5bd; min-height:55px; border:none; outline:none; resize:none; cursor:pointer;">
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

                        <!-- HIDDEN FILTER INPUTS -->
                        <input type="hidden" name="filter_age" id="filter-age-input">
                        <input type="hidden" name="filter_breed" id="filter-breed-input">
                        <input type="hidden" name="filter_audience" id="filter-audience-input">
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

                    <a href="{{ route('location') }}" class="btn btn-link text-white d-flex align-items-center gap-1">
                        <i class="bi bi-geo-alt fs-4"></i>
                        <span id="location-text">Location</span>
                    </a>

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

            <!-- POSTS LOOP -->
            @foreach ($posts as $post)
                <div class="post mb-4" data-interest="{{ $post->interest }}"
                    style=" background-color:#292D37; border-radius:8px; overflow:hidden; max-width:800px;">
                    {{-- POST HEADER (User Info) --}}
                    <div class="p-3 d-flex align-items-center gap-3 border-bottom" style="border-color:#1B1E25;">
                        <a href="{{ route('profile.show', $post->user->id) }}"
                            class="d-flex align-items-center gap-3 text-decoration-none">
                            <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('assets/usericon.png') }}"
                                class="rounded-circle" style="width:40px; height:40px; object-fit:cover;">
                            <div>
                                <h6 class=" text-white mb-0" style="font-size:14px;">
                                    {{ $post->user->pet_name ?? 'Unknown User' }}
                                </h6>
                                <small class="text-white mb-0" style="font-size:12px;">
                                    {{ $post->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </a>
                        <div class="ms-auto position-relative">
                            <button class="btn btn-link text-white p-0" onclick="toggleMenu({{ $post->id }})"
                                style="border: none; background: none;">
                                <i class=" bi bi-three-dots-vertical fs-5"></i>
                            </button>
                            <div id="menu-{{ $post->id }}" class="dropdown-menu-custom"
                                style="position:absolute; right:0; top:30px; display:none;">
                                @if($post->user_id === auth()->id())
                                    <!-- EDIT -->
                                    <a href="{{ route('posts.edit', $post->id) }}" class=" dropdown-item-custom">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                    <!-- DELETE -->
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item-custom text-danger" style="border: none;">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                @else
                                    <!-- REPORT -->
                                    <form action="{{ route('posts.report', $post->id) }}" method="POST" style="margin: 0;"> 
                                        @csrf
                                        <button type="submit" class="dropdown-item-custom" style="border: none;">
                                            <i class="bi bi-flag me-2"></i>Report
                                        </button>
                                    </form>
                                    <!-- BLOCK/UNBLOCK -->
                                    @if(auth()->user()->hasBlocked($post->user_id))
                                        <button type="button" class="dropdown-item-custom" onclick="unblockUser({{ $post->user_id }})"
                                            style="border: none;">
                                            <i class="bi bi-unlock me-2"></i>Unblock
                                        </button>
                                    @else
                                        <button type="button" class="dropdown-item-custom" onclick="blockUser({{ $post->user_id }})"
                                            style="border: none;">
                                            <i class="bi bi-ban me-2"></i>Block
                                        </button>
                                    @endif
                                @endif
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
                            <img src="{{ asset('storage/' . $post->photo) }}" style="width:100%; height:auto; max-height:450px; object-fit:cover; display:block;">
                        </div>
                    @endif
                    {{-- TAGS (AGE / BREED / AUDIENCE / LOCATION / INTEREST) --}}
                    @if($post->city || $post->age || $post->breed || $post->interest || $post->audience)
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
                            {{-- BREED (ORANGE) --}}
                            @if($post->breed)
                                <span class="badge text-white d-flex align-items-center gap-1"
                                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                                    <i class="bi bi-tags-fill" style="color:#facc15;"></i>
                                    Breed: {{ $post->breed }}
                                </span>
                            @endif
                            {{-- AUDIENCE (PURPLE) --}}
                            @if($post->audience)
                                <span class="badge text-white d-flex align-items-center gap-1"
                                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                                    <i class="bi bi-people" style="color:#a78bfa;"></i>
                                    Audience: {{ ucfirst($post->audience) }}
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
                            <span>{{ $post->comments_count }} Comment{{ $post->comments_count != 1 ? 's' : '' }}</span>
                        </a>
                    </div>
                </div>
            @endforeach

        </div>

        @section('right-sidebar')
            <style>
                .contact-item-wrapper {
                    position: relative;
                }

                .contact-menu-btn {
                    background: none;
                    border: none;
                    color: #8b95a5;
                    cursor: pointer;
                    font-size: 1.2rem;
                    padding: 4px;
                    opacity: 0;
                    transition: opacity 0.2s, color 0.2s;
                }

                .contact-item-wrapper:hover .contact-menu-btn {
                    opacity: 1;
                }

                .contact-menu-btn:hover {
                    color: white;
                }

                .contact-dropdown {
                    position: absolute;
                    top: 100%;
                    right: 0;
                    background-color: #2a3142;
                    border: 1px solid #3d4757;
                    border-radius: 0.5rem;
                    min-width: 150px;
                    z-index: 1000;
                    display: none;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
                    margin-top: 4px;
                }

                .contact-dropdown.show {
                    display: block;
                }

                .contact-dropdown-item {
                    padding: 0.75rem 1rem;
                    color: white;
                    cursor: pointer;
                    text-decoration: none;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-size: 0.9rem;
                    border: none;
                    background: none;
                    width: 100%;
                    text-align: left;
                    transition: background-color 0.2s;
                }

                .contact-dropdown-item:hover {
                    background-color: #3d4757;
                }

                .contact-dropdown-item.danger {
                    color: #ef4444;
                }

                .contact-dropdown-item.danger:hover {
                    background-color: rgba(239, 68, 68, 0.1);
                }
            </style>

            <div
                style="background-color: transparent; border: none; border-radius: 0; padding: 0; display: flex; flex-direction: column;">
                <h6 style="color: white; font-weight: 600; margin-bottom: 16px; margin-top: 0;">Contacts</h6>

                <div style="display: flex; flex-direction: column; gap: 8px; flex: 1;">
                    @forelse($friends ?? [] as $friend)
                        <div class="contact-item-wrapper"
                            style="position: relative; display: flex; align-items: center; gap: 12px; padding: 10px; background-color: #1e2230; border-radius: 8px; border: 1px solid #3d4557; transition: all 0.2s ease;"
                            onmouseover="this.style.backgroundColor='#2d323f'; this.style.borderColor='#4a5568';"
                            onmouseout="this.style.backgroundColor='#1e2230'; this.style.borderColor='#3d4557';">

                            <a href="{{ route('messages.conversation', $friend->id) }}"
                                style="display: flex; align-items: center; gap: 12px; text-decoration: none; flex: 1; min-width: 0;">
                                <img src="{{ $friend->avatar ? asset('storage/' . $friend->avatar) : asset('assets/usericon.png') }}"
                                    style="width: 2.75rem; height: 2.75rem; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #3d4557;">
                                <div style="flex: 1; min-width: 0;">
                                    <p
                                        style="color: white; margin: 0; font-size: 0.875rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $friend->pet_name }}
                                    </p>
                                    <p style="color: #8b95a5; margin: 0; font-size: 0.75rem;">
                                        {{ $friend->pet_breed }}
                                    </p>
                                </div>
                            </a>

                            <button class="contact-menu-btn" onclick="toggleContactMenu(event, {{ $friend->id }})">
                                ⋮
                            </button>
                            <div class="contact-dropdown" id="menu-{{ $friend->id }}">
                                <button class="contact-dropdown-item" onclick="unfriendUser({{ $friend->id }})">
                                    <i class="bi bi-person-x"></i> Unfriend
                                </button>
                                @if(auth()->user()->hasBlocked($friend->id))
                                    <button class="contact-dropdown-item" onclick="unblockUser({{ $friend->id }})">
                                        <i class="bi bi-lock-fill"></i> Unblock
                                    </button>
                                @else
                                    <button class="contact-dropdown-item danger" onclick="blockUser({{ $friend->id }})">
                                        <i class="bi bi-ban"></i> Block
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p style="color: #8b95a5; font-size: 0.875rem; text-align: center; margin: 0; padding: 16px 0;">No contacts yet</p>
                    @endforelse
                </div>
            </div>
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
            <button class="position-absolute top-0 end-0 m-2 btn-close btn-close-white" onclick="closeBreedModal()"></button>
            <h5>Select Breed</h5>
            <ul id="breedList"></ul>
        </div>
    </div>

    <!-- Audience Selection Modal -->
    <div id="audienceModal" class="modal-container" style="display:none;">
        <div class="modal-content-custom position-relative">
            <button class="position-absolute top-0 end-0 m-2 btn-close btn-close-white" onclick="closeAudienceModal()"></button>
            <h5>Select Audience</h5>
            <ul id="audienceList"></ul>
        </div>
    </div>

    @push('scripts')
        <script>
            // DEFINE AGES AND BREEDS ARRAYS
            const ages = [
                { value: 1, label: '1 year old' },
                { value: 2, label: '2 years old' },
                { value: 3, label: '3 years old' },
                { value: 4, label: '4 years old' },
                { value: 5, label: '5 years old' }
            ];
            
            const breeds = [
                'Shih Tzu',
                'Golden Retriever',
                'Labrador',
                'Beagle',
                'Poodle'
            ];

            // AGE MODAL FUNCTIONS
            function showAgeModal() {
                const ageList = document.getElementById('ageList');
                ageList.innerHTML = '';
                // Add "All Ages" option
                const allLi = document.createElement('li');
                allLi.textContent = 'All Ages';
                allLi.onclick = () => selectAge('');
                ageList.appendChild(allLi);
                ages.forEach(ageObj => {
                    const li = document.createElement('li');
                    li.textContent = ageObj.label;
                    li.onclick = () => selectAge(ageObj.value);
                    ageList.appendChild(li);
                });
                document.getElementById('ageModal').style.display = 'flex';
            }
            
            function closeAgeModal() {
                document.getElementById('ageModal').style.display = 'none';
            }
            
            function selectAge(age) {
                closeAgeModal();
                filterPosts('age', age);
            }

            // BREED MODAL FUNCTIONS
            function showBreedModal() {
                const breedList = document.getElementById('breedList');
                breedList.innerHTML = '';
                
                // Add "All Breeds" option
                const allLi = document.createElement('li');
                allLi.textContent = 'All Breeds';
                allLi.onclick = () => selectBreed('');
                breedList.appendChild(allLi);
                
                breeds.forEach(breed => {
                    const li = document.createElement('li');
                    li.textContent = breed;
                    li.onclick = () => selectBreed(breed);
                    breedList.appendChild(li);
                });
                document.getElementById('breedModal').style.display = 'flex';
            }
            
            function closeBreedModal() {
                document.getElementById('breedModal').style.display = 'none';
            }
            
            function selectBreed(breed) {
                closeBreedModal();
                filterPosts('breed', breed);
            }

            // AUDIENCE MODAL FUNCTIONS
            function showAudienceModal() {
                const audienceList = document.getElementById('audienceList');
                audienceList.innerHTML = '';
                
                // Add "All" option
                const allLi = document.createElement('li');
                allLi.textContent = 'All';
                allLi.onclick = () => selectAudience('');
                audienceList.appendChild(allLi);
                
                const audiences = ['Public', 'Friends Only'];
                audiences.forEach(audience => {
                    const li = document.createElement('li');
                    li.textContent = audience;
                    li.onclick = () => selectAudience(audience);
                    audienceList.appendChild(li);
                });
                document.getElementById('audienceModal').style.display = 'flex';
            }
            
            function closeAudienceModal() {
                document.getElementById('audienceModal').style.display = 'none';
            }
            
            function selectAudience(audience) {
                closeAudienceModal();
                filterPosts('audience', audience);
            }

            // FILTER POSTS FUNCTION
            let currentFilters = {
                age: '',
                breed: '',
                audience: ''
            };

            function filterPosts(filterType, value) {
                // Map filterType to backend param
                let paramMap = {
                    age: 'filter_age',
                    breed: 'filter_breed',
                    audience: 'filter_audience'
                };
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set(paramMap[filterType], value);
                // Remove empty filters
                if (!value) urlParams.delete(paramMap[filterType]);
                window.location.href = window.location.pathname + '?' + urlParams.toString();
            }

            // PHOTO BUTTON FUNCTIONALITY
            const photoBtn = document.getElementById('photoBtn');
            const imageInput = document.getElementById('post-image');

            if (!photoBtn || !imageInput) {
                console.error("photoBtn or post-image NOT FOUND");
            } else {
                // Add loading indicator
                let postLoading = document.createElement('div');
                postLoading.id = 'post-upload-loading';
                postLoading.style = 'display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);z-index:9999;justify-content:center;align-items:center;';
                postLoading.innerHTML = '<div style="color:white;font-size:1.2rem;"><span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span> Uploading...</div>';
                document.body.appendChild(postLoading);

                photoBtn.addEventListener('click', function () {
                    imageInput.click();
                });

                imageInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    // Show loading
                    postLoading.style.display = 'flex';
                    photoBtn.disabled = true;

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
                        postLoading.style.display = 'none';
                        photoBtn.disabled = false;
                        if (data.success) {
                            window.location.href = "{{ route('posting.page') }}";
                        } else {
                            console.error("Failed to store session");
                        }
                    })
                    .catch(err => {
                        postLoading.style.display = 'none';
                        photoBtn.disabled = false;
                        console.error(err);
                    });
                });
            }

            // POST MENU TOGGLE
            function toggleMenu(postId) {
                const menu = document.getElementById(`menu-${postId}`);
                
                // Close all other menus
                document.querySelectorAll('.dropdown-menu-custom').forEach(dropdown => {
                    if (dropdown.id !== `menu-${postId}`) {
                        dropdown.style.display = 'none';
                    }
                });

                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            }

            // CONTACT MENU FUNCTIONS
            function toggleContactMenu(event, friendId) {
                event.preventDefault();
                event.stopPropagation();
                const menu = document.getElementById(`menu-${friendId}`);

                // Close all other menus
                document.querySelectorAll('.contact-dropdown.show').forEach(dropdown => {
                    if (dropdown.id !== `menu-${friendId}`) {
                        dropdown.classList.remove('show');
                    }
                });

                menu.classList.toggle('show');
            }

            function unfriendUser(userId) {
                if (confirm('Are you sure you want to unfriend this user?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/friend/unfriend/${userId}`;
                    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function blockUser(userId) {
                if (confirm('Are you sure you want to block this user? You will no longer see their posts or messages.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/posts/${userId}/block`;
                    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function unblockUser(userId) {
                if (confirm('Are you sure you want to unblock this user?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/posts/${userId}/unblock`;
                    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function (event) {
                if (!event.target.closest('.contact-item-wrapper') && !event.target.closest('[onclick^="toggleMenu"]')) {
                    document.querySelectorAll('.contact-dropdown.show').forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                    document.querySelectorAll('.dropdown-menu-custom').forEach(dropdown => {
                        dropdown.style.display = 'none';
                    });
                }
            });
        </script>
    @endpush
    @push('scripts')
    <script>
        // Real-time update using Laravel Echo
        if (window.Echo) {
            window.Echo.channel('posts')
                .listen('PostCreated', (e) => {
                    // Force full page reload for all users on new post
                    window.location.reload();
                });
        } else {
            // Fallback: Poll every 5 seconds for new posts
            let lastPostId = null;
            function checkNewPosts() {
                const firstPost = document.querySelector('.post[data-interest]');
                if (!firstPost) return;
                const currentId = firstPost.getAttribute('data-interest');
                if (lastPostId === null) {
                    lastPostId = currentId;
                } else if (currentId !== lastPostId) {
                    window.location.reload();
                }
            }
            setInterval(checkNewPosts, 5000);
        }
    </script>
    @endpush

@endsection