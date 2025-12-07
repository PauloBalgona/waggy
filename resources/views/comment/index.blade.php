@extends('navbar.nav2')
@section('title', 'Comments')
@section('body-class', 'bg-gray-900')

@section('content')
    <div
        style="min-height: 100vh; background-color: #111827; color: white; padding: 1.5rem; max-width: 600px; margin: 0 auto;">

        {{-- Success Message --}}
        @if(session('success'))
            <div
                style="background-color: #10b981; color: white; padding: 0.75rem; border-radius: 0.375rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        {{-- POST CARD --}}
        <div style="background-color: #1f2937; border-radius: 0.5rem; overflow: hidden; margin-bottom: 1rem;">

            {{-- Post Header --}}
            <div
                style="padding: 0.75rem; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid #374151;">
                <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('assets/usericon.png') }}"
                    style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; object-fit: cover; background-color: #374151;">
                <div>
                    <p style="font-weight: 600; font-size: 0.875rem; color: white; margin: 0;">
                        {{ $post->user->pet_name ?? $post->user->name }}
                    </p>
                    <p style="font-size: 0.75rem; color: #9ca3af; margin: 0;">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- Post Message --}}
            @if($post->message)
                <div style="padding: 0.75rem;">
                    <p style="color: white; font-size: 0.875rem; margin: 0;">{{ $post->message }}</p>
                </div>
            @endif

            {{-- Post Image --}}
            @if($post->photo)
                <div style="width: 100%; max-height: 400px; overflow: hidden; background-color: #111827;">
                    <img src="{{ asset('storage/' . $post->photo) }}"
                        style="width: 100%; height: auto; max-height: 400px; object-fit: cover; display: block;">
                </div>
            @endif

            {{-- Tags (Location, Age, Breed, Interest) --}}
            @if($post->city || $post->age || $post->breed || $post->interest)
                <div style="padding: 0.75rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    @if($post->city && $post->province)
                        <span
                            style="background-color: #374151; color: white; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 9999px;">
                            📍 {{ $post->city }}, {{ $post->province }}
                        </span>
                    @endif

                    @if($post->age)
                        <span
                            style="background-color: #374151; color: white; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 9999px;">
                            📅 Age: {{ $post->age }}
                        </span>
                    @endif

                    @if($post->breed)
                        <span
                            style="background-color: #374151; color: white; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 9999px;">
                            🏷️ {{ $post->breed }}
                        </span>
                    @endif

                    @if($post->interest)
                        <span
                            style="background-color: #374151; color: white; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 9999px;">
                            ❤️ {{ $post->interest }}
                        </span>
                    @endif
                </div>
            @endif

            {{-- Like & Comment Buttons --}}
            <div
                style="border-top: 1px solid #374151; padding: 0.75rem; display: flex; align-items: center; justify-content: space-around;">
                {{-- Like Button --}}
                <button type="button" onclick="toggleLike()"
                    style="display: flex; align-items: center; gap: 0.5rem; color: white; background: none; border: none; cursor: pointer; font-size: 0.875rem; padding: 0.5rem 1rem; transition: color 0.3s ease;"
                    onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='white'">
                    <svg id="heartIcon" style="width: 1.25rem; height: 1.25rem; transition: all 0.3s ease;" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                    <span><span id="likeCount">{{ $post->likes_count ?? 3 }}</span> Likes</span>
                </button>

                {{-- Comment Button --}}
                <button type="button" id="commentBtn" onclick="showCommentBox()"
                    style="display: flex; align-items: center; gap: 0.5rem; color: white; background: none; border: none; cursor: pointer; font-size: 0.875rem; padding: 0.5rem 1rem; transition: color 0.3s ease;"
                    onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='white'">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <span>Comment</span>
                </button>
            </div>

            {{-- Comment Form (Hidden by default) --}}
            <div id="commentBox" style="display: none; border-top: 1px solid #374151; padding: 0.75rem;">
                <div style="display: flex; gap: 0.5rem; align-items: flex-start;">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}"
                        style="width: 2rem; height: 2rem; border-radius: 9999px; object-fit: cover; flex-shrink: 0; background-color: #374151;">

                    <div style="flex: 1;">
                        <form action="{{ route('comments.store', $post->id) }}" method="POST">
                            @csrf
                            <textarea name="content" rows="2"
                                style="width: 100%; background-color: #111827; color: white; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; resize: none; border: none; outline: none;"
                                placeholder="Write a comment..." onfocus="this.style.outline='2px solid #3b82f6'"
                                onblur="this.style.outline='none'" required>{{ old('content') }}</textarea>

                            <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                <button type="submit"
                                    style="padding: 0.375rem 1rem; background-color: #3b82f6; color: white; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; transition: background-color 0.3s ease;"
                                    onmouseover="this.style.backgroundColor='#2563eb'"
                                    onmouseout="this.style.backgroundColor='#3b82f6'">
                                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Post
                                </button>
                                <button type="button" onclick="hideCommentBox()"
                                    style="padding: 0.375rem 1rem; background-color: #374151; color: white; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.3s ease;"
                                    onmouseover="this.style.backgroundColor='#4b5563'"
                                    onmouseout="this.style.backgroundColor='#374151'">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Comment List --}}
        <div style="margin-top: 1.5rem;">
            <h4 style="font-size: 0.875rem; font-weight: 600; color: #9ca3af; margin-bottom: 1rem;">Comments</h4>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse ($comments as $comment)
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('assets/usericon.png') }}"
                            style="width: 2rem; height: 2rem; border-radius: 9999px; object-fit: cover; flex-shrink: 0; background-color: #374151;">

                        <div style="flex: 1;">
                            <div style="background-color: #1f2937; border-radius: 0.5rem; padding: 0.75rem;">
                                <p style="font-weight: 600; font-size: 0.875rem; color: white; margin: 0;">
                                    {{ $comment->user->pet_name ?? $comment->user->name }}
                                </p>
                                <p style="font-size: 0.875rem; color: #d1d5db; margin: 0.25rem 0 0 0;">{{ $comment->content }}
                                </p>
                            </div>
                            <span
                                style="color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem; margin-left: 0.25rem; display: inline-block;">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p style="color: #9ca3af;">No comments yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        let isLiked = {{ isset($post->likes) && $post->likes->where('user_id', auth()->id())->count() > 0 ? 'true' : 'false' }};
        let likeCount = {{ $post->likes_count ?? 3 }};

        function toggleLike() {
            const heartIcon = document.getElementById('heartIcon');
            const likeCountSpan = document.getElementById('likeCount');

            if (isLiked) {
                likeCount--;
                heartIcon.setAttribute('fill', 'none');
                heartIcon.setAttribute('stroke', 'currentColor');
            } else {
                likeCount++;
                heartIcon.setAttribute('fill', '#ef4444');
                heartIcon.setAttribute('stroke', '#ef4444');
            }

            isLiked = !isLiked;
            likeCountSpan.textContent = likeCount;

            // Submit like to backend
            fetch("{{ route('posts.like', $post->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
        }

        function showCommentBox() {
            document.getElementById('commentBtn').style.display = 'none';
            document.getElementById('commentBox').style.display = 'block';
            document.querySelector('#commentBox textarea').focus();
        }

        function hideCommentBox() {
            document.getElementById('commentBtn').style.display = 'flex';
            document.getElementById('commentBox').style.display = 'none';
            document.querySelector('#commentBox textarea').value = '';
        }

        // Set initial heart state
        if (isLiked) {
            document.getElementById('heartIcon').setAttribute('fill', '#ef4444');
            document.getElementById('heartIcon').setAttribute('stroke', '#ef4444');
        }
    </script>
@endsection