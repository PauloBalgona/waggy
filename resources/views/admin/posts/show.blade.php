@extends('admin.layout')
@section('admin-title', 'Post Details')

@section('admin-content')
    <div class="row g-4">
        <!-- Post Content -->
        <div class="col-lg-8">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div style="display: flex; align-items: start; margin-bottom: 20px;">
                    <div>
                        <h5 style="color: #333; margin: 0; font-weight: 600;">{{ $post->user->name }}</h5>
                        <small style="color: #666;">{{ $post->created_at->format('M d, Y H:i') }}</small>
                    </div>
                </div>

                <p style="color: #333; margin-bottom: 20px; line-height: 1.6;">{{ $post->message }}</p>

                @if($post->photo)
                    <img src="{{ asset('storage/' . $post->photo) }}" alt="Post image" class="img-fluid rounded mb-4" style="max-height: 400px; object-fit: cover; width: 100%; border: 1px solid #e5e7eb;">
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-md-2">
                        <small style="color: #666; display: block;">Age</small>
                        <p style="color: #333; margin: 0; font-weight: 500;">{{ $post->age ?? '-' }}</p>
                    </div>
                    <div class="col-md-2">
                        <small style="color: #666; display: block;">Breed</small>
                        <p style="color: #333; margin: 0; font-weight: 500;">{{ $post->breed ?? '-' }}</p>
                    </div>
                    <div class="col-md-2">
                        <small style="color: #666; display: block;">Province</small>
                        <p style="color: #333; margin: 0; font-weight: 500;">{{ $post->province ?? '-' }}</p>
                    </div>
                    <div class="col-md-2">
                        <small style="color: #666; display: block;">City</small>
                        <p style="color: #333; margin: 0; font-weight: 500;">{{ $post->city ?? '-' }}</p>
                    </div>
                    <div class="col-md-2">
                        <small style="color: #666; display: block;">Interest</small>
                        <p style="color: #333; margin: 0; font-weight: 500;">{{ $post->interest ?? '-' }}</p>
                    </div>
                    <div class="col-md-2">
                        <small style="color: #666; display: block;">Audience</small>
                        <p style="margin: 0;"><span class="badge" style="background-color: #1e3a8a; color: #fff;">{{ $post->audience ?? 'Public' }}</span></p>
                    </div>
                </div>

                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background-color: #dc2626; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 500; cursor: pointer;" onclick="return confirm('Delete this post?')">Delete Post</button>
                </form>
            </div>
        </div>

        <!-- Post Stats -->
        <div class="col-lg-4">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Post Stats</h5>
                <div style="margin-bottom: 20px;">
                    <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
                        <small style="color: #666; display: block;">Total Likes</small>
                        <h3 style="color: #1e3a8a; margin: 0;">{{ $post->likes()->count() }}</h3>
                    </div>
                </div>
                <div style="margin-bottom: 10px;">
                    <div style="background-color: #f9fafb; padding: 12px; border-radius: 8px; border: 1px solid #e5e7eb;">
                        <small style="color: #666; display: block;">Total Comments</small>
                        <h4 style="color: #1e3a8a; margin: 0;">{{ $post->comments_count ?? $post->comments()->count() }}</h4>
                    </div>
                </div>
            </div>

            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Author</h5>
                <a href="{{ route('admin.users.show', $post->user->id) }}" style="text-decoration: none;">
                    <div style="display: flex; align-items: center; margin-bottom: 20px;">
                        @if($post->user->avatar)
                            <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="{{ $post->user->name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px; border: 2px solid #1e3a8a;">
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #1e3a8a; color: #fff; margin-right: 15px; font-weight: bold;">
                                {{ strtoupper(substr($post->user->email, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h6 style="color: #333; margin: 0; font-weight: 600;">{{ $post->user->email }}</h6>
                            <small style="color: #666;">{{ $post->user->name }}</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
