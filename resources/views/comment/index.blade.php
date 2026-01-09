@extends('navbar.nav2')
    @section('title', 'Comments')
    @section('body-class', 'bg-gray-900')

    @section('content')
        <div
            style="background-color: #111827; color: white; padding: 1.5rem;max-width: 540px; margin: 0 auto; border-radius: 20px;">

            {{-- Success Message --}}
            @if(session('success'))
                <div
                    style="background-color: #10b981; color: white; padding: 0.75rem; border-radius: 2rem; margin-bottom: 1rem;">
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
                                style="background-color: #374151; color: white; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <i class="bi bi-geo-alt" style="font-size: 0.875rem;"></i> {{ $post->city }}, {{ $post->province }}
                            </span>
                        @endif

                        @if($post->age)
                            <span
                                style="background-color: #374151; color: white; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <i class="bi bi-calendar3" style="font-size: 0.875rem;"></i> Age: {{ $post->getAgeFormattedAttribute() }}
                            </span>
                        @endif

                        @if($post->breed)
                            <span
                                style="background-color: #374151; color: white; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <i class="bi bi-tag" style="font-size: 0.875rem;"></i> {{ $post->breed }}
                            </span>
                        @endif

                        @if($post->interest)
                            <span
                                style="background-color: #374151; color: white; font-size: 0.75rem; padding: 0.375rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <i class="bi bi-heart" style="font-size: 0.875rem;"></i> {{ $post->interest }}
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
                            <form id="commentForm">
                                @csrf
                                <textarea name="content" id="commentInput" rows="2"
                                    style="width: 100%; background-color: #111827; color: white; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; resize: none; border: none; outline: none;"
                                    placeholder="Write a comment..." onfocus="this.style.outline='2px solid #3b82f6'"
                                    onblur="this.style.outline='none'" required>{{ old('content') }}</textarea>

                                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                    <button type="submit"
                                        style="padding: 0.375rem 1rem; background-color: #3b82f6; color: white; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; transition: background-color 0.3s ease;"
                                        onmouseover="this.style.backgroundColor='#2563eb'"
                                        onmouseout="this.style.backgroundColor='#3b82f6'">
                                        Comment
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
                <div data-comments-list style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse ($comments as $comment)
                        <div style="display: flex; gap: 0.75rem; align-items: flex-start;" id="comment-wrapper-{{ $comment->id }}" data-comment-id="{{ $comment->id }}">
                            <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('assets/usericon.png') }}"
                                style="width: 2rem; height: 2rem; border-radius: 9999px; object-fit: cover; flex-shrink: 0; background-color: #374151;">

                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem;">
                                    <div style="flex: 1;">
                                        <div id="comment-content-{{ $comment->id }}" style="background-color: #1f2937; border-radius: 0.5rem; padding: 0.75rem;">
                                            <p style="font-weight: 600; font-size: 0.875rem; color: white; margin: 0;">
                                                {{ $comment->user->pet_name ?? $comment->user->name }}
                                            </p>
                                            <p style="font-size: 0.875rem; color: #d1d5db; margin: 0.25rem 0 0 0; word-break: break-word;">{{ $comment->content }}</p>
                                        </div>
                                        <div id="comment-edit-{{ $comment->id }}" style="display: none; margin-top: 0.5rem;">
                                            <textarea id="comment-textarea-{{ $comment->id }}" style="width: 100%; background-color: #111827; color: white; border-radius: 0.5rem; padding: 0.75rem; border: 1px solid #374151; resize: none; font-size: 0.875rem;" rows="3">{{ $comment->content }}</textarea>
                                            <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                                <button type="button" onclick="saveCommentEdit({{ $comment->id }})"
                                                    style="padding: 0.375rem 1rem; background-color: #10b981; color: white; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.3s ease;"
                                                    onmouseover="this.style.backgroundColor='#059669'"
                                                    onmouseout="this.style.backgroundColor='#10b981'">
                                                    Save
                                                </button>
                                                <button type="button" onclick="cancelCommentEdit({{ $comment->id }})"
                                                    style="padding: 0.375rem 1rem; background-color: #374151; color: white; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.3s ease;"
                                                    onmouseover="this.style.backgroundColor='#4b5563'"
                                                    onmouseout="this.style.backgroundColor='#374151'">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                        <div style="position: relative;">
                                            <button class="comment-menu-btn" onclick="toggleCommentMenu(event, {{ $comment->id }})"
                                                style="background: none; border: none; color: #9ca3af; cursor: pointer; font-size: 1.25rem; padding: 0; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; transition: color 0.3s ease;"
                                                onmouseover="this.style.color='white'" onmouseout="this.style.color='#9ca3af'">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            
                                            <div id="comment-menu-{{ $comment->id }}" class="comment-menu"
                                                style="display: none; position: absolute; right: 0; top: 100%; background-color: #1f2937; border-radius: 0.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.5); z-index: 1000; min-width: 150px; border: 1px solid #374151;">
                                                
                                                @if(auth()->id() === $comment->user_id)
                                                    <button type="button" onclick="startCommentEdit({{ $comment->id }})"
                                                        style="display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.75rem 1rem; background: none; border: none; color: #60a5fa; cursor: pointer; font-size: 0.875rem; transition: background-color 0.2s; border-bottom: 1px solid #374151;"
                                                        onmouseover="this.style.backgroundColor='rgba(59,130,246,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>
                                                @endif
                                                
                                                <button type="button" onclick="deleteComment({{ $comment->id }})"
                                                    style="display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.75rem 1rem; background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.875rem; transition: background-color 0.2s;"
                                                    onmouseover="this.style.backgroundColor='rgba(239,68,68,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <span
                                    style="color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem; margin-left: 0.25rem; display: inline-block;">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                            <!-- Replies Section -->
                            @if($comment->replies->count() > 0)
                                <div style="margin-left: 2.75rem; margin-top: 0.75rem; border-left: 2px solid #374151; padding-left: 0.75rem;" id="replies-{{ $comment->id }}">
                                    @foreach($comment->replies as $reply)
                                        <div style="padding: 0.5rem 0; position: relative;" id="reply-{{ $reply->id }}">
                                            <div style="display: flex; gap: 0.75rem;">
                                                <img src="{{ $reply->user->avatar ? asset('storage/' . $reply->user->avatar) : asset('assets/usericon.png') }}"
                                                    style="width: 1.75rem; height: 1.75rem; border-radius: 50%; object-fit: cover;">
                                                <div style="flex: 1;">
                                                    <div style="background-color: #111827; border-radius: 0.5rem; padding: 0.5rem 0.75rem;">
                                                        <p style="color: white; font-weight: 600; margin: 0; font-size: 0.75rem;">{{ $reply->user->pet_name }}</p>
                                                        <p style="color: #d1d5db; margin: 0.25rem 0 0 0; font-size: 0.75rem;">{{ $reply->content }}</p>
                                                    </div>
                                                    <p style="color: #6b7280; font-size: 0.75rem; margin: 0.25rem 0 0 0;">{{ $reply->created_at->diffForHumans() }}</p>
                                                </div>
                                                @if(auth()->id() === $reply->user_id || auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                                    <button type="button" onclick="deleteReplyIndex({{ $reply->id }})" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.25rem 0.5rem; font-size: 0.875rem; opacity: 0.7;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Reply Form -->
                            <div style="margin-left: 2.75rem; margin-top: 0.5rem;">
                                <form onsubmit="submitReplyIndex(event, {{ $comment->id }})" style="display: flex; gap: 0.5rem;">
                                    @csrf
                                    <input type="text" placeholder="Reply..." id="reply-input-{{ $comment->id }}" style="flex: 1; padding: 0.375rem 0.5rem; background-color: #111827; border: 1px solid #374151; border-radius: 0.5rem; color: white; font-size: 0.75rem;">
                                    <button type="submit" style="padding: 0.375rem 0.75rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.75rem; white-space: nowrap;">Reply</button>
                                </form>
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

            function toggleCommentMenu(event, commentId) {
                event.stopPropagation();
                const menu = document.getElementById(`comment-menu-${commentId}`);
                const allMenus = document.querySelectorAll('.comment-menu');
                
                allMenus.forEach(m => {
                    if (m !== menu) m.style.display = 'none';
                });
                
                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            }

            function startCommentEdit(commentId) {
                const contentDiv = document.getElementById(`comment-content-${commentId}`);
                const editDiv = document.getElementById(`comment-edit-${commentId}`);
                const menu = document.getElementById(`comment-menu-${commentId}`);
                
                contentDiv.style.display = 'none';
                editDiv.style.display = 'block';
                menu.style.display = 'none';
                document.getElementById(`comment-textarea-${commentId}`).focus();
            }

            function cancelCommentEdit(commentId) {
                const contentDiv = document.getElementById(`comment-content-${commentId}`);
                const editDiv = document.getElementById(`comment-edit-${commentId}`);
                
                contentDiv.style.display = 'block';
                editDiv.style.display = 'none';
            }

            function saveCommentEdit(commentId) {
                const textarea = document.getElementById(`comment-textarea-${commentId}`);
                const content = textarea.value.trim();

                if (!content) {
                    alert('Comment cannot be empty');
                    return;
                }

                fetch(`/comments/${commentId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const contentDiv = document.getElementById(`comment-content-${commentId}`);
                        const contentP = contentDiv.querySelector('p:last-child');
                        contentP.textContent = data.content;
                        
                        const editDiv = document.getElementById(`comment-edit-${commentId}`);
                        contentDiv.style.display = 'block';
                        editDiv.style.display = 'none';
                    } else {
                        alert('Failed to update comment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update comment');
                });
            }

            function deleteComment(commentId) {
                if (confirm('Are you sure you want to delete this comment?')) {
                    // Remove from DOM immediately with fade animation
                        const commentDiv = document.getElementById(`comment-wrapper-${commentId}`);
                        if (commentDiv) {
                            commentDiv.style.opacity = '0';
                            commentDiv.style.transition = 'opacity 0.3s ease';
                            setTimeout(() => {
                                commentDiv.remove();
                            }, 300);
                        }
                        // Remove all replies under this comment
                        const repliesDiv = document.getElementById(`replies-${commentId}`);
                        if (repliesDiv) repliesDiv.innerHTML = '';
                    
                    // Send delete request in background (don't wait for response)
                    fetch(`/comments/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                }
            }

            function editComment(commentId) {
                alert('Edit functionality coming soon!');
            }

            // Close menus when clicking elsewhere
            document.addEventListener('click', function() {
                document.querySelectorAll('.comment-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            });

                function submitReplyIndex(event, commentId) {
                    event.preventDefault();
                    const input = document.getElementById(`reply-input-${commentId}`);
                    const content = input.value.trim();

                    if (!content) {
                        alert('Reply cannot be empty');
                        return;
                    }

                    fetch(`/comments/${commentId}/replies`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: content })
                    })
                    .then(response => {
                        if (!response.ok) {
                            const ct = response.headers.get('content-type') || '';
                            if (ct.includes('application/json')) {
                                return response.json().then(err => { throw new Error(err.message || JSON.stringify(err)); });
                            }
                            return response.text().then(txt => { throw new Error('Server returned: ' + txt.substring(0, 300)); });
                        }
                        const ct = response.headers.get('content-type') || '';
                        if (!ct.includes('application/json')) {
                            return response.text().then(txt => { throw new Error('Expected JSON but got HTML: ' + txt.substring(0, 300)); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            input.value = '';

                            // Find or create replies container
                            const commentWrapper = document.getElementById(`comment-wrapper-${commentId}`);
                            let repliesContainer = commentWrapper.parentNode.querySelector(`[id^="reply-${commentId}"]`)?.closest('div');

                            // Create container if it doesn't exist
                            if (!repliesContainer) {
                                repliesContainer = document.createElement('div');
                                repliesContainer.style.cssText = 'margin-left: 2.75rem; margin-top: 0.75rem; border-left: 2px solid #374151; padding-left: 0.75rem;';
                                commentWrapper.parentNode.insertBefore(repliesContainer, commentWrapper.nextSibling);
                            }

                            // Add new reply to DOM safely
                            const reply = data.reply;
                            const replyDiv = document.createElement('div');
                            replyDiv.id = `reply-${reply.id}`;
                            replyDiv.style.padding = '0.5rem 0';
                            replyDiv.style.position = 'relative';

                            const row = document.createElement('div');
                            row.style.display = 'flex';
                            row.style.gap = '0.75rem';

                            const img = document.createElement('img');
                            img.src = reply.user.avatar ? '/storage/' + reply.user.avatar : '/assets/usericon.png';
                            img.style.width = '1.75rem';
                            img.style.height = '1.75rem';
                            img.style.borderRadius = '50%';
                            img.style.objectFit = 'cover';

                            const body = document.createElement('div');
                            body.style.flex = '1';

                            const bubble = document.createElement('div');
                            bubble.style.backgroundColor = '#111827';
                            bubble.style.borderRadius = '0.5rem';
                            bubble.style.padding = '0.5rem 0.75rem';

                            const nameP = document.createElement('p');
                            nameP.style.color = 'white';
                            nameP.style.fontWeight = '600';
                            nameP.style.margin = '0';
                            nameP.style.fontSize = '0.75rem';
                            nameP.textContent = reply.user.pet_name || reply.user.name || '';

                            const contentP = document.createElement('p');
                            contentP.style.color = '#d1d5db';
                            contentP.style.margin = '0.25rem 0 0 0';
                            contentP.style.fontSize = '0.75rem';
                            contentP.textContent = reply.content;

                            bubble.appendChild(nameP);
                            bubble.appendChild(contentP);

                            const timeP = document.createElement('p');
                            timeP.style.color = '#6b7280';
                            timeP.style.fontSize = '0.75rem';
                            timeP.style.margin = '0.25rem 0 0 0';
                            timeP.textContent = 'just now';

                            body.appendChild(bubble);
                            body.appendChild(timeP);

                            const delBtn = document.createElement('button');
                            delBtn.type = 'button';
                            delBtn.style.background = 'none';
                            delBtn.style.border = 'none';
                            delBtn.style.color = '#ef4444';
                            delBtn.style.cursor = 'pointer';
                            delBtn.style.padding = '0.25rem 0.5rem';
                            delBtn.style.fontSize = '0.875rem';
                            delBtn.style.opacity = '0.7';
                            delBtn.innerHTML = '<i class="bi bi-trash"></i>';
                            delBtn.addEventListener('click', function () { deleteReplyIndex(reply.id); });

                            row.appendChild(img);
                            row.appendChild(body);
                            row.appendChild(delBtn);
                            replyDiv.appendChild(row);
                            repliesContainer.appendChild(replyDiv);

                            // Optional: increment any visible comment count (if present)
                            const countEl = document.querySelector('[data-post-comments-count]');
                            if (countEl) {
                                const val = parseInt(countEl.textContent || '0', 10) || 0;
                                countEl.textContent = (val + 1).toString();
                            }
                        } else {
                            alert('Failed to post reply');
                        }
                    })
                    .catch(error => {
                        console.error('Error posting reply:', error);
                        if (error.message && (error.message.includes('Page Expired') || error.message.includes('<!DOCTYPE') || error.message.toLowerCase().includes('login'))) {
                            alert('Session may have expired. Please refresh the page and try again.');
                            return;
                        }
                        alert('Failed to post reply');
                    });
                }

                function deleteReplyIndex(replyId) {
                    if (confirm('Delete this reply?')) {
                        const replyDiv = document.getElementById(`reply-${replyId}`);
                        if (replyDiv) {
                            replyDiv.style.opacity = '0';
                            replyDiv.style.transition = 'opacity 0.3s ease';
                            setTimeout(() => {
                                replyDiv.remove();
                            }, 300);
                        }

                                // Optional: decrement any visible comment count (if present)
                                const countEl = document.querySelector('[data-post-comments-count]');
                                if (countEl) {
                                    const val = parseInt(countEl.textContent || '0', 10) || 0;
                                    countEl.textContent = Math.max(0, val - 1).toString();
                                }

                        fetch(`/replies/${replyId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                        });
                    }
                }
        </script>

       <script>
    // Show error below comment box
    let commentError = document.createElement('div');
    commentError.id = 'commentErrorMsg';
    commentError.style.display = 'none';
    commentError.style.color = '#ef4444';
    commentError.style.margin = '0.5rem 0 0 0';
    commentError.style.fontSize = '0.9rem';
    document.getElementById('commentForm').parentNode.appendChild(commentError);

    // Allow Enter to submit, Shift+Enter for newline
    document.getElementById('commentInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('commentForm').dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        }
    });

    document.getElementById('commentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        commentError.style.display = 'none';
        commentError.textContent = '';

        const content = document.getElementById('commentInput').value.trim();
        if (!content) return;

        fetch("{{ route('comments.store', $post->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ content })
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(err => {
                    throw new Error(err.message || 'Failed to post comment');
                });
            }
            return res.json();
        })
        .then(data => {
            if (!data.success) {
                commentError.textContent = data.message || 'Failed to comment. Please try again.';
                commentError.style.display = 'block';
                return;
            }

            document.getElementById('commentInput').value = '';
            hideCommentBox();

            // 🔥 ADD COMMENT TO DOM WITH FULL FUNCTIONALITY
            const list = document.querySelector('[data-comments-list]');
            const commentId = data.comment.id;
            const avatarUrl = data.comment.user.avatar ? '/storage/' + data.comment.user.avatar : '/assets/usericon.png';
            const userName = data.comment.user.name;
            const commentContent = data.comment.content;
            
            // Helper function to escape HTML
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            const commentWrapper = document.createElement('div');
            commentWrapper.id = `comment-wrapper-${commentId}`;
            commentWrapper.setAttribute('data-comment-id', commentId);
            commentWrapper.style.cssText = 'display: flex; gap: 0.75rem; align-items: flex-start';

            commentWrapper.innerHTML = `
                <img src="${escapeHtml(avatarUrl)}"
                    style="width: 2rem; height: 2rem; border-radius: 9999px; object-fit: cover; flex-shrink: 0; background-color: #374151;">

                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem;">
                        <div style="flex: 1;">
                            <div id="comment-content-${commentId}" style="background-color: #1f2937; border-radius: 0.5rem; padding: 0.75rem;">
                                <p style="font-weight: 600; font-size: 0.875rem; color: white; margin: 0;">${escapeHtml(userName)}</p>
                                <p style="font-size: 0.875rem; color: #d1d5db; margin: 0.25rem 0 0 0; word-break: break-word;">${escapeHtml(commentContent)}</p>
                            </div>
                            <div id="comment-edit-${commentId}" style="display: none; margin-top: 0.5rem;">
                                <textarea id="comment-textarea-${commentId}" style="width: 100%; background-color: #111827; color: white; border-radius: 0.5rem; padding: 0.75rem; border: 1px solid #374151; resize: none; font-size: 0.875rem;" rows="3">${escapeHtml(commentContent)}</textarea>
                                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                    <button type="button" onclick="saveCommentEdit(${commentId})"
                                        style="padding: 0.375rem 1rem; background-color: #10b981; color: white; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.3s ease;"
                                        onmouseover="this.style.backgroundColor='#059669'"
                                        onmouseout="this.style.backgroundColor='#10b981'">
                                        Save
                                    </button>
                                    <button type="button" onclick="cancelCommentEdit(${commentId})"
                                        style="padding: 0.375rem 1rem; background-color: #374151; color: white; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.3s ease;"
                                        onmouseover="this.style.backgroundColor='#4b5563'"
                                        onmouseout="this.style.backgroundColor='#374151'">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div style="position: relative;">
                            <button class="comment-menu-btn" onclick="toggleCommentMenu(event, ${commentId})"
                                style="background: none; border: none; color: #9ca3af; cursor: pointer; font-size: 1.25rem; padding: 0; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; transition: color 0.3s ease;"
                                onmouseover="this.style.color='white'" onmouseout="this.style.color='#9ca3af'">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            
                            <div id="comment-menu-${commentId}" class="comment-menu"
                                style="display: none; position: absolute; right: 0; top: 100%; background-color: #1f2937; border-radius: 0.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.5); z-index: 1000; min-width: 150px; border: 1px solid #374151;">
                                
                                <button type="button" onclick="startCommentEdit(${commentId})"
                                    style="display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.75rem 1rem; background: none; border: none; color: #60a5fa; cursor: pointer; font-size: 0.875rem; transition: background-color 0.2s; border-bottom: 1px solid #374151;"
                                    onmouseover="this.style.backgroundColor='rgba(59,130,246,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                
                                <button type="button" onclick="deleteComment(${commentId})"
                                    style="display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.75rem 1rem; background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.875rem; transition: background-color 0.2s;"
                                    onmouseover="this.style.backgroundColor='rgba(239,68,68,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <span style="color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem; margin-left: 0.25rem; display: inline-block;">
                        just now
                    </span>
                </div>
            `;

            list.insertBefore(commentWrapper, list.firstChild);

            // Add reply section after the comment
            const replySection = document.createElement('div');
            replySection.style.cssText = 'margin-left: 2.75rem; margin-top: 0.5rem;';
            
            const replyForm = document.createElement('form');
            replyForm.onsubmit = function(e) { submitReplyIndex(e, commentId); };
            replyForm.style.cssText = 'display: flex; gap: 0.5rem;';
            
            const replyInput = document.createElement('input');
            replyInput.type = 'text';
            replyInput.id = `reply-input-${commentId}`;
            replyInput.placeholder = 'Reply...';
            replyInput.style.cssText = 'flex: 1; padding: 0.375rem 0.5rem; background-color: #111827; border: 1px solid #374151; border-radius: 0.5rem; color: white; font-size: 0.75rem;';
            
            const replyButton = document.createElement('button');
            replyButton.type = 'submit';
            replyButton.textContent = 'Reply';
            replyButton.style.cssText = 'padding: 0.375rem 0.75rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.75rem; white-space: nowrap;';
            
            replyForm.appendChild(replyInput);
            replyForm.appendChild(replyButton);
            replySection.appendChild(replyForm);

            commentWrapper.parentNode.insertBefore(replySection, commentWrapper.nextSibling);
        })
        .catch(err => {
            console.error('Comment error:', err);
            commentError.textContent = err.message || 'Error posting comment. Please try again.';
            commentError.style.display = 'block';
        });
    });
    </script>

    <script>
const postId = {{ $post->id }};
window.Echo.channel('post.' + postId)
    .listen('.comment.posted', (e) => {
        const c = e.comment;
        if (document.querySelector(`[data-comment-id="${c.id}"]`)) return;
        const list = document.querySelector('[data-comments-list]');
        if (!list) return;
        const avatar = c.user.avatar ? '/storage/' + c.user.avatar : '/assets/usericon.png';
        const div = document.createElement('div');
        div.id = `comment-wrapper-${c.id}`;
        div.dataset.commentId = c.id;
        div.style.cssText = 'display:flex;gap:0.75rem;align-items:flex-start';
        div.innerHTML = `
            <img src="${avatar}"
                style="width:2rem;height:2rem;border-radius:9999px;object-fit:cover;background:#374151">
            <div style="flex:1">
                <div style="background:#1f2937;border-radius:0.5rem;padding:0.75rem">
                    <p style="font-weight:600;font-size:0.875rem;margin:0;color:white">
                        ${c.user.pet_name ?? c.user.name}
                    </p>
                    <p style="font-size:0.875rem;color:#d1d5db;margin-top:0.25rem">
                        ${c.content}
                    </p>
                </div>
                <span style="font-size:0.75rem;color:#6b7280">just now</span>
            </div>
        `;
        list.insertBefore(div, list.firstChild);
    })
    .listen('.reply.posted', (e) => {
        const r = e.reply;
        if (document.getElementById(`reply-${r.id}`)) return;
        const repliesDiv = document.getElementById(`replies-${r.comment_id}`);
        if (!repliesDiv) return;
        const avatar = r.user.avatar ? '/storage/' + r.user.avatar : '/assets/usericon.png';
        const div = document.createElement('div');
        div.id = `reply-${r.id}`;
        div.style.cssText = 'display:flex;gap:.5rem;margin-bottom:.25rem';
        div.innerHTML = `<strong>${r.user.pet_name ?? r.user.name}</strong> <span>${r.content}</span>`;
        repliesDiv.appendChild(div);
    })
    .listen('.comment.deleted', (e) => {
        const el = document.getElementById(`comment-wrapper-${e.commentId}`);
        if (el) el.remove();
        // Remove replies section/form
        const repliesDiv = document.getElementById(`replies-${e.commentId}`);
        if (repliesDiv) repliesDiv.remove();
    })
    .listen('.reply.deleted', (e) => {
        const el = document.getElementById(`reply-${e.replyId}`);
        if (el) el.remove();
    })
    .listen('.comment.edited', (e) => {
        const el = document.getElementById(`comment-wrapper-${e.commentId}`);
        if (el) {
            const p = el.querySelector('p:last-child');
            if (p) p.textContent = e.content;
        }
    })
    .listen('.reply.edited', (e) => {
        const el = document.getElementById(`reply-${e.replyId}`);
        if (el) {
            const span = el.querySelector('span');
            if (span) span.textContent = e.content;
        }
    });
</script>
    @endsection