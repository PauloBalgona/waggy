@props(['post'])
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
    </div>
    @if($post->message)
        <div class="p-3">
            <p class="text-white mb-0" style="font-size:14px; line-height:1.5;">{{ $post->message }}</p>
        </div>
    @endif
    @if($post->photo)
        <div class="w-100"
            style="background-color:#1B1E25; max-height:450px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
            <img src="{{ asset('storage/' . $post->photo) }}" style="width:100%; height:auto; max-height:450px; object-fit:cover; display:block;">
        </div>
    @endif
    {{-- TAGS (AGE / BREED / AUDIENCE / LOCATION / INTEREST) --}}
    @if($post->city || $post->age || $post->breed || $post->interest || $post->audience)
        <div class="p-3 d-flex flex-wrap gap-2">
            @if($post->city && $post->province)
                <span class="badge text-white d-flex align-items-center gap-1"
                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                    <i class="bi bi-geo-alt" style="color:#0dcaf0;"></i>
                    {{ $post->city }}, {{ $post->province }}
                </span>
            @endif
            @if($post->age)
                <span class="badge text-white d-flex align-items-center gap-1"
                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                    <i class="bi bi-calendar" style="color:#ffc107;"></i>
                    Age: {{ $post->age }}
                </span>
            @endif
            @if($post->breed)
                <span class="badge text-white d-flex align-items-center gap-1"
                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                    <i class="bi bi-tags-fill" style="color:#facc15;"></i>
                    Breed: {{ $post->breed }}
                </span>
            @endif
            @if($post->audience)
                <span class="badge text-white d-flex align-items-center gap-1"
                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                    <i class="bi bi-people" style="color:#a78bfa;"></i>
                    Audience: {{ ucfirst($post->audience) }}
                </span>
            @endif
            @if($post->interest)
                <span class="badge text-white d-flex align-items-center gap-1"
                    style="background-color:#1B1E25; font-size:11px; padding:6px 12px; border-radius:20px; font-weight:normal;">
                    <i class="bi bi-heart" style="color:#ff4d6d;"></i>
                    {{ $post->interest }}
                </span>
            @endif
        </div>
    @endif
    <div class="d-flex justify-content-around border-top p-2" style="border-color:#1B1E25;">
        <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit"
                class="btn btn-link d-flex align-items-center gap-2 {{ $post->likes->where('user_id', auth()->id())->count() > 0 ? 'text-primary' : 'text-white' }}"
                style="font-size:13px; text-decoration:none;">
                <i class="bi bi-heart" style="font-size:18px;"></i>
                <span>{{ $post->likes_count }} Like{{ $post->likes_count != 1 ? 's' : '' }}</span>
            </button>
        </form>
        <a href="{{ route('comments.index', $post->id) }}"
            class="btn btn-link text-white d-flex align-items-center gap-2"
            style="font-size:13px; text-decoration:none;">
            <i class="bi bi-chat-dots" style="font-size:18px;"></i>
            <span>{{ $post->comments_count }} Comment{{ $post->comments_count != 1 ? 's' : '' }}</span>
        </a>
    </div>
</div>
