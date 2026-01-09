@extends('navbar.nav2')
@section('title', 'Post - Waggy')
@section('body-class', 'bg-gray-900')

@section('content')
    <style>
        .main-wrapper {
            padding: 0 !important;
        }

        .post-container {
            max-width: 80%;
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 80vh;
        }

       .post-card {
    background-color: #1c2230;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    margin: 24px auto;
    border: 1px solid #2d3748;
    max-width: 650px;
    min-height: 300px; /* bawasan height */
}

.post-image {
    width: 100%;
    height: 300px;
    border-radius: 12px;
    object-fit: contain; /* show full image, not cropped */
    margin-bottom: 20px;
    background-color: #fff; /* white bg for transparent images */
    display: block;
    border: 1px solid #2d3748;
}

.post-content {
    line-height: 1.5; /* medyo mas compact lang */
    margin-bottom: 20px;
}

/* --- lahat ng iba, intact --- */

.main-wrapper {
    padding: 0 !important;
}

.post-container {
    max-width: 80%;
    margin: 0;
    padding: 0;
    width: 100%;
    min-height: 80vh;
}

.post-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.post-user-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.post-user-info {
    flex: 1;
}

.post-user-name {
    color: white;
    font-weight: 700;
    font-size: 16px;
    margin: 0;
}

.post-user-breed {
    color: #8b95a5;
    font-size: 13px;
    margin: 4px 0 0 0;
}

.post-time {
    color: #6b7280;
    font-size: 12px;
    margin: 0;
}

.post-actions {
    display: flex;
    gap: 12px;
    padding-top: 20px;
    border-top: 1px solid #2d3748;
}

.post-action-btn {
    flex: 1;
    padding: 12px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
    border: 1px solid rgba(59, 130, 246, 0.2);
    color: #8b95a5;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-weight: 500;
    font-size: 14px;
}

.post-action-btn:hover {
    color: #3b82f6;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(59, 130, 246, 0.1));
    border-color: rgba(59, 130, 246, 0.4);
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: white;
    text-decoration: none;
    margin: 24px auto;
    margin-bottom: 0;
    padding: 12px 20px;
    border-radius: 8px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    display: block;
    width: fit-content;
    margin-left: 24px;
    margin-top: 24px;
}

.back-btn:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    transform: translateY(-2px);
    color: white;
}

.back-btn:active {
    transform: translateY(0px);
}

.back-btn i {
    font-size: 16px;
}

.post-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
    margin: 24px 0;
    padding: 20px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(59, 130, 246, 0.02));
    border-radius: 12px;
    border: 1px solid rgba(59, 130, 246, 0.1);
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.detail-label {
    color: #8b95a5;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: white;
    font-size: 15px;
    font-weight: 600;
}

.comment-menu-btn {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
    transition: all 0.2s;
    font-size: 16px;
}

.comment-menu-btn:hover {
    color: white;
    background-color: rgba(255, 255, 255, 0.05);
}

.comment-menu {
    position: absolute;
    right: 0;
    top: 100%;
    background-color: #1c2230;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    z-index: 1000;
    min-width: 150px;
    border: 1px solid #2d3748;
}

.comment-menu-item {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    padding: 10px 16px;
    background: none;
    border: none;
    color: #60a5fa;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.comment-menu-item:hover {
    background-color: rgba(59, 130, 246, 0.1);
}

.comment-menu-item.delete {
    color: #ef4444;
}

.comment-menu-item.delete:hover {
    background-color: rgba(239, 68, 68, 0.1);
}

    </style>

    <div class="post-container">
        <a href="{{ route('home') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
            Back to Feed
        </a>

        <div class="post-card">
            <div class="post-header">
                <a href="{{ route('profile.show', $post->user->id) }}" style="text-decoration: none;">
                    <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('assets/usericon.png') }}"
                        alt="Avatar"
                        class="post-user-avatar">
                </a>

                <div class="post-user-info">
                    <a href="{{ route('profile.show', $post->user->id) }}" style="text-decoration: none;">
                        <p class="post-user-name">{{ $post->user->pet_name }}</p>
                        <p class="post-user-breed">{{ $post->user->pet_breed }}</p>
                    </a>
                </div>
            </div>

            @if($post->photo)
                <img src="{{ asset('storage/' . $post->photo) }}" alt="Post image" class="post-image">
            @endif

            <div class="post-content">
                {!! nl2br(e($post->message)) !!}
            </div>

            <!-- Post Details Section -->
            <div class="post-details">
                @if($post->breed)
                    <div class="detail-item">
                        <span class="detail-label"><i class="bi bi-paw"></i> Breed</span>
                        <span class="detail-value">{{ $post->breed }}</span>
                    </div>
                @endif

                @if($post->age)
                    <div class="detail-item">
                        <span class="detail-label"><i class="bi bi-calendar"></i> Age</span>
                        <span class="detail-value">{{ $post->getAgeFormattedAttribute() }}</span>
                    </div>
                @endif

                @if($post->province || $post->city)
                    <div class="detail-item">
                        <span class="detail-label"><i class="bi bi-geo-alt"></i> Location</span>
                        <span class="detail-value">
                            @if($post->city && $post->province)
                                {{ $post->city }}, {{ $post->province }}
                            @elseif($post->city)
                                {{ $post->city }}
                            @elseif($post->province)
                                {{ $post->province }}
                            @endif
                        </span>
                    </div>
                @endif

                @if($post->audience)
                    <div class="detail-item">
                        <span class="detail-label"><i class="bi bi-eye"></i> Audience</span>
                        <span class="detail-value">{{ ucfirst($post->audience) }}</span>
                    </div>
                @endif

                @if($post->interest)
                    <div class="detail-item">
                        <span class="detail-label"><i class="bi bi-heart"></i> Interest</span>
                        <span class="detail-value">{{ $post->interest }}</span>
                    </div>
                @endif
            </div>

            <p class="post-time">
                {{ $post->created_at->format('M d, Y \a\t g:i A') }}
            </p>

            <div class="post-actions">
                <button id="like-btn" class="post-action-btn" onclick="likePost({{ $post->id }})">
                    <i id="like-icon" class="bi bi-heart{{ $post->likes->where('user_id', auth()->id())->count() > 0 ? '-fill text-danger' : '' }}"></i>
                    <span id="like-count">{{ $post->likes_count }} Likes</span>
                </button>
                <button class="post-action-btn" onclick="scrollToComments()">
                    <i class="bi bi-chat"></i>
                    <span>Comment</span>
                </button>
            </div>
        </div>

        <!-- Comments Section -->
        <div id="comments-section" class="post-card">
            <h3 style="color: white; margin-top: 0; margin-bottom: 16px;">Comments</h3>

            <div id="comments-list">
            @forelse($post->comments as $comment)
                <div style="padding: 12px 0; border-bottom: 1px solid #2d3748; position: relative;" id="comment-post-{{ $comment->id }}">
                    <div style="display: flex; gap: 12px; margin-bottom: 8px; position: relative;">
                        <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('assets/usericon.png') }}"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        <div style="flex: 1;">
                            <div id="comment-view-{{ $comment->id }}" style="display: block;">
                                <div style="background-color: #1c2230; border-radius: 8px; padding: 12px; border: 1px solid #2d3748;">
                                    <p style="color: white; font-weight: 600; margin: 0; font-size: 14px;">{{ $comment->user->pet_name }}</p>
                                    <p id="comment-text-{{ $comment->id }}" style="color: #e5e7eb; margin: 6px 0 0 0; font-size: 14px; word-break: break-word;">{{ $comment->content }}</p>
                                </div>
                                <p style="color: #6b7280; font-size: 12px; margin: 6px 0 0 0;">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            
                            <div id="comment-edit-view-{{ $comment->id }}" style="display: none;">
                                <textarea id="edit-textarea-{{ $comment->id }}" style="width: 100%; background-color: #2a3142; color: white; border: 1px solid #3d4557; border-radius: 6px; padding: 8px; resize: none; font-size: 14px; margin-top: 8px;" rows="3">{{ $comment->content }}</textarea>
                                <div style="display: flex; gap: 8px; margin-top: 8px;">
                                    <button type="button" onclick="saveEditPost({{ $comment->id }})" style="padding: 6px 12px; background-color: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600;">Save</button>
                                    <button type="button" onclick="cancelEditPost({{ $comment->id }})" style="padding: 6px 12px; background-color: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600;">Cancel</button>
                                </div>
                            </div>
                        </div>

                        @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                            <div style="position: relative;">
                                <button type="button" class="comment-menu-btn" onclick="toggleCommentMenuPost(event, {{ $comment->id }})"
                                    style="padding: 4px 8px;">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>

                                <div id="comment-menu-{{ $comment->id }}" class="comment-menu" style="display: none;">
                                    @if(auth()->id() === $comment->user_id)
                                        <button type="button" class="comment-menu-item" onclick="startEditPost({{ $comment->id }})">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                    @endif
                                    <button type="button" class="comment-menu-item delete" onclick="deleteCommentPost({{ $comment->id }})">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                    <!-- Replies Section -->
                    @if($comment->replies->count() > 0)
                        <div style="margin-left: 52px; margin-top: 12px; border-left: 2px solid #2d3748; padding-left: 12px;">
                            @foreach($comment->replies as $reply)
                                <div style="padding: 8px 0; position: relative;" id="reply-{{ $reply->id }}">
                                    <div style="display: flex; gap: 12px;">
                                        <img src="{{ $reply->user->avatar ? asset('storage/' . $reply->user->avatar) : asset('assets/usericon.png') }}"
                                            style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                        <div style="flex: 1;">
                                            <div style="background-color: #2a3142; border-radius: 6px; padding: 8px; border: 1px solid #3d4557;">
                                                <p style="color: white; font-weight: 600; margin: 0; font-size: 12px;">{{ $reply->user->pet_name }}</p>
                                                <p style="color: #e5e7eb; margin: 4px 0 0 0; font-size: 12px;">{{ $reply->content }}</p>
                                            </div>
                                            <p style="color: #6b7280; font-size: 11px; margin: 4px 0 0 0;">{{ $reply->created_at->diffForHumans() }}</p>
                                        </div>
                                        @if(auth()->id() === $reply->user_id || auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                            <button type="button" onclick="deleteReply({{ $reply->id }})" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 4px 8px; font-size: 12px; opacity: 0.7; hover:opacity: 1;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Reply Form -->
                    <div style="margin-left: 52px; margin-top: 8px;">
                        <form onsubmit="submitReply(event, {{ $comment->id }})" style="display: flex; gap: 8px;">
                            @csrf
                            <input type="text" placeholder="Write a reply..." id="reply-input-{{ $comment->id }}" style="flex: 1; padding: 6px 8px; background-color: #2a3142; border: 1px solid #3d4557; border-radius: 4px; color: white; font-size: 12px;" onclick="focusReplyInput({{ $comment->id }})">
                            <button type="submit" style="padding: 6px 12px; background-color: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; white-space: nowrap;">Reply</button>
                        </form>
                    </div>

            @empty
                <p style="color: #6b7280; text-align: center; padding: 20px 0;">No comments yet</p>
            @endforelse
            </div>

            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #2d3748;">
                <form action="{{ route('comments.store', $post->id) }}" method="POST" style="display: flex; gap: 12px;">
                    @csrf
                    <textarea id="comment-content" name="content" placeholder="Write a comment..." style="flex: 1; padding: 10px; background-color: #2a3142; border: 1px solid #3d4557; border-radius: 6px; color: white; resize: none; min-height: 36px; max-height: 80px;" rows="1" required></textarea>
                    <button id="comment-submit-btn" type="submit" style="padding: 10px 10px; background-color: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; white-space: nowrap;">SEND</button>
                </form>
            </div>
        </div>
    </div>

    <script>
                // AJAX comment submission (form-encoded, Laravel default)
                const commentForm = document.querySelector('form[action="{{ route('comments.store', $post->id) }}"]');
                if (commentForm) {
                    const textarea = document.getElementById('comment-content');
                    const btn = document.getElementById('comment-submit-btn');
                    // Allow Enter to submit (no Shift)
                    textarea.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            btn.click();
                        }
                    });
                    commentForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const content = textarea.value.trim();
                        if (!content) {
                            alert('Comment cannot be empty');
                            return;
                        }
                        btn.disabled = true;
                        const formData = new FormData(commentForm);
                        fetch(commentForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(async response => {
                            btn.disabled = false;
                            if (!response.ok) {
                                const text = await response.text();
                                throw new Error(text);
                            }
                            // Laravel returns redirect for non-ajax, so just reload
                            textarea.value = '';
                            location.reload();
                        })
                        .catch(error => {
                            btn.disabled = false;
                            alert('Failed to post comment. Please try again.');
                            console.error('Error:', error);
                        });
                    });
                }

                // AJAX comment deletion
                function deleteCommentPost(commentId) {
                    if (confirm('Are you sure you want to delete this comment?')) {
                        const commentDiv = document.getElementById(`comment-post-${commentId}`);
                        if (commentDiv) {
                            commentDiv.style.opacity = '0';
                            commentDiv.style.transition = 'opacity 0.3s ease';
                            setTimeout(() => {
                                commentDiv.remove();
                            }, 300);
                        }
                        fetch(`/comments/${commentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        }).then(response => {
                            if (!response.ok) {
                                alert('Failed to delete comment.');
                                console.error('Delete failed:', response.status);
                            }
                        }).catch(error => {
                            alert('Failed to delete comment.');
                            console.error('Error:', error);
                        });
                    }
                }
        function likePost(postId) {
            const btn = document.getElementById('like-btn');
            const icon = document.getElementById('like-icon');
            const countSpan = document.getElementById('like-count');
            btn.disabled = true;
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                btn.disabled = false;
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                if (!response.ok) {
                    const text = await response.text();
                    throw new Error(text);
                }
                // Try to get updated like count from the DOM
                let current = parseInt(countSpan.textContent);
                let liked = icon.classList.contains('bi-heart-fill');
                if (liked) {
                    icon.classList.remove('bi-heart-fill', 'text-danger');
                    icon.classList.add('bi-heart');
                    countSpan.textContent = (current - 1) + ' Likes';
                } else {
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill', 'text-danger');
                    countSpan.textContent = (current + 1) + ' Likes';
                }
            })
            .catch(error => {
                btn.disabled = false;
                alert('Failed to like/unlike post. Please try again.');
                console.error('Error:', error);
            });
        }

        function scrollToComments() {
            document.getElementById('comments-section').scrollIntoView({ behavior: 'smooth' });
        }

        function toggleCommentMenuPost(event, commentId) {
            event.stopPropagation();
            const menu = document.getElementById(`comment-menu-${commentId}`);
            const allMenus = document.querySelectorAll('.comment-menu');

            allMenus.forEach(m => {
                if (m !== menu) m.style.display = 'none';
            });

            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        function startEditPost(commentId) {
            const viewDiv = document.getElementById(`comment-view-${commentId}`);
            const editDiv = document.getElementById(`comment-edit-view-${commentId}`);
            const menu = document.getElementById(`comment-menu-${commentId}`);

            viewDiv.style.display = 'none';
            editDiv.style.display = 'block';
            menu.style.display = 'none';
            document.getElementById(`edit-textarea-${commentId}`).focus();
        }

        function cancelEditPost(commentId) {
            const viewDiv = document.getElementById(`comment-view-${commentId}`);
            const editDiv = document.getElementById(`comment-edit-view-${commentId}`);

            viewDiv.style.display = 'block';
            editDiv.style.display = 'none';
        }

        function saveEditPost(commentId) {
            const textarea = document.getElementById(`edit-textarea-${commentId}`);
            const content = textarea.value.trim();

            if (!content) {
                alert('Comment cannot be empty');
                return;
            }

            fetch(`/comments/${commentId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ content: content })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const textElement = document.getElementById(`comment-text-${commentId}`);
                    textElement.textContent = data.content;

                    const viewDiv = document.getElementById(`comment-view-${commentId}`);
                    const editDiv = document.getElementById(`comment-edit-view-${commentId}`);

                    viewDiv.style.display = 'block';
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

        function deleteCommentPost(commentId) {
            if (confirm('Are you sure you want to delete this comment?')) {
                // Remove from DOM immediately with fade animation - don't wait for server response
                const commentDiv = document.getElementById(`comment-post-${commentId}`);
                if (commentDiv) {
                    commentDiv.style.opacity = '0';
                    commentDiv.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => {
                        commentDiv.remove();
                    }, 300);
                }
                
                // Send delete request in background (but don't wait for it before removing from DOM)
                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (!response.ok) {
                        console.error('Delete failed:', response.status);
                    }
                }).catch(error => {
                    console.error('Error:', error);
                });
            }
        }

        // Close menus when clicking elsewhere
        document.addEventListener('click', function() {
            document.querySelectorAll('.comment-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        });

            function focusReplyInput(commentId) {
                const input = document.getElementById(`reply-input-${commentId}`);
                if (input) {
                    input.focus();
                }
            }

            function submitReply(event, commentId) {
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
                        console.error('Response status:', response.status);
                        return response.text().then(text => {
                            console.error('Response body:', text.substring(0, 500));
                            throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
                        });
                    }

                    const ct = response.headers.get('content-type') || '';
                    if (!ct.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Expected JSON but got:', ct || 'no content-type');
                            console.error('Response body:', text.substring(0, 500));
                            throw new Error(`Expected JSON but received ${ct || 'unknown'} content type`);
                        });
                    }

                    return response.json();
                })
                .then(data => {
                    if (data && data.success && data.reply) {
                        input.value = '';

                        // Get or create replies container
                        let repliesContainer = document.querySelector(`#comment-post-${commentId} ~ div[style*="margin-left"]`);
                        if (!repliesContainer) {
                            // Create replies container if it doesn't exist
                            const commentDiv = document.getElementById(`comment-post-${commentId}`);
                            repliesContainer = document.createElement('div');
                            repliesContainer.style.cssText = 'margin-left: 52px; margin-top: 12px; border-left: 2px solid #2d3748; padding-left: 12px;';
                            commentDiv.parentNode.insertBefore(repliesContainer, commentDiv.nextSibling);
                        }

                        // Add new reply to DOM using safe DOM APIs
                        const reply = data.reply;
                        const replyWrapper = document.createElement('div');
                        replyWrapper.id = `reply-${reply.id}`;
                        replyWrapper.style.padding = '8px 0';

                        const row = document.createElement('div');
                        row.style.display = 'flex';
                        row.style.gap = '12px';

                        const avatar = document.createElement('img');
                        avatar.src = reply.user && reply.user.avatar ? '/storage/' + reply.user.avatar : '/assets/usericon.png';
                        avatar.style.width = '32px';
                        avatar.style.height = '32px';
                        avatar.style.borderRadius = '50%';
                        avatar.style.objectFit = 'cover';

                        const flex = document.createElement('div');
                        flex.style.flex = '1';

                        const box = document.createElement('div');
                        box.style.backgroundColor = '#2a3142';
                        box.style.borderRadius = '6px';
                        box.style.padding = '8px';
                        box.style.border = '1px solid #3d4557';

                        const nameP = document.createElement('p');
                        nameP.style.color = 'white';
                        nameP.style.fontWeight = '600';
                        nameP.style.margin = '0';
                        nameP.style.fontSize = '12px';
                        nameP.textContent = (reply.user && reply.user.pet_name) || 'User';

                        const contentP = document.createElement('p');
                        contentP.style.color = '#e5e7eb';
                        contentP.style.margin = '4px 0 0 0';
                        contentP.style.fontSize = '12px';
                        contentP.textContent = reply.content;

                        box.appendChild(nameP);
                        box.appendChild(contentP);

                        const timeP = document.createElement('p');
                        timeP.style.color = '#6b7280';
                        timeP.style.fontSize = '11px';
                        timeP.style.margin = '4px 0 0 0';
                        timeP.textContent = 'just now';

                        flex.appendChild(box);
                        flex.appendChild(timeP);

                        const delBtn = document.createElement('button');
                        delBtn.type = 'button';
                        delBtn.style.background = 'none';
                        delBtn.style.border = 'none';
                        delBtn.style.color = '#ef4444';
                        delBtn.style.cursor = 'pointer';
                        delBtn.style.padding = '4px 8px';
                        delBtn.style.fontSize = '12px';
                        delBtn.style.opacity = '0.7';
                        delBtn.innerHTML = '<i class="bi bi-trash"></i>';
                        delBtn.onclick = function() { deleteReply(reply.id); };

                        row.appendChild(avatar);
                        row.appendChild(flex);
                        row.appendChild(delBtn);

                        replyWrapper.appendChild(row);
                        repliesContainer.appendChild(replyWrapper);
                    } else {
                        console.error('Unexpected response structure:', data);
                        alert('Failed to post reply: Invalid server response');
                    }
                })
                .catch(error => {
                    console.error('Error posting reply:', error);
                    alert('Failed to post reply: ' + (error.message || 'Unknown error'));
                });
            }

            function deleteReply(replyId) {
                if (confirm('Delete this reply?')) {
                    const replyDiv = document.getElementById(`reply-${replyId}`);
                    if (replyDiv) {
                        replyDiv.style.opacity = '0';
                        replyDiv.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => {
                            replyDiv.remove();
                        }, 300);
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
function submitReply(e, commentId) {
    e.preventDefault();
    const input = document.getElementById('reply-input-' + commentId);
    const content = input.value.trim();
    if (!content) return;
    input.disabled = true;
    // Optimistically add reply to UI
    let repliesDiv = document.querySelector(`#comment-post-${commentId} .replies-list`);
    if (!repliesDiv) {
        repliesDiv = document.createElement('div');
        repliesDiv.className = 'replies-list';
        const commentDiv = document.getElementById('comment-post-' + commentId);
        commentDiv.appendChild(repliesDiv);
    }
    const optimisticId = 'optimistic-reply-' + Date.now();
    const optimisticHtml = `<div id="${optimisticId}" style="padding:8px 0;opacity:.6;"><div style="display:flex;gap:12px;"><img src='{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/usericon.png') }}' style='width:32px;height:32px;border-radius:50%;object-fit:cover;'><div style='flex:1;'><div style='background-color:#2a3142;border-radius:6px;padding:8px;border:1px solid #3d4557;'><p style='color:white;font-weight:600;margin:0;font-size:12px;'>{{ auth()->user()->pet_name }}</p><p style='color:#e5e7eb;margin:4px 0 0 0;font-size:12px;'>${escapeHtml(content)}</p></div><p style='color:#6b7280;font-size:11px;margin:4px 0 0 0;'>just now</p></div></div></div>`;
    repliesDiv.insertAdjacentHTML('beforeend', optimisticHtml);
    input.value = '';

    const formData = new FormData();
    formData.append('content', content);
    fetch(`/comments/${commentId}/replies`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async res => {
        input.disabled = false;
        if (!res.ok) {
            let msg = 'Failed to send reply.';
            try { const data = await res.json(); if (data && data.message) msg = data.message; } catch {}
            alert(msg);
            const optimistic = document.getElementById(optimisticId);
            if (optimistic) optimistic.remove();
            return;
        }
        const data = await res.json();
        // Remove ALL optimistic replies for this comment
        const allOptimistic = repliesDiv.querySelectorAll('[id^="optimistic-reply-"]');
        allOptimistic.forEach(el => el.remove());
        if (data && data.reply) {
            // Render confirmed reply
            const reply = data.reply;
            const html = `<div style=\"padding:8px 0;position:relative;\"><div style=\"display:flex;gap:12px;\"><img src='${reply.user.avatar ? '/storage/' + reply.user.avatar : '/assets/usericon.png'}' style=\"width:32px;height:32px;border-radius:50%;object-fit:cover;\"><div style=\"flex:1;\"><div style=\"background-color:#2a3142;border-radius:6px;padding:8px;border:1px solid #3d4557;\"><p style=\"color:white;font-weight:600;margin:0;font-size:12px;\">${escapeHtml(reply.user.pet_name)}</p><p style=\"color:#e5e7eb;margin:4px 0 0 0;font-size:12px;\">${escapeHtml(reply.content)}</p></div><p style=\"color:#6b7280;font-size:11px;margin:4px 0 0 0;\">just now</p></div></div></div>`;
            repliesDiv.insertAdjacentHTML('beforeend', html);
        }
    })
    .catch(() => {
        input.disabled = false;
        const optimistic = document.getElementById(optimisticId);
        if (optimistic) optimistic.remove();
        alert('Failed to send reply.');
    });
}
// Escape HTML utility
function escapeHtml(text) {
    return text.replace(/[&<>"']/g, function (c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
}
</script>
@endsection
