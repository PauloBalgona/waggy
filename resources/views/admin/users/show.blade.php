@extends('admin.layout')
@section('admin-title', 'User Details')

@section('admin-content')
    <div class="row g-4">
        <!-- User Info -->
        <div class="col-lg-4">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div class="text-center mb-4">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->email }}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #1e3a8a;">
                    @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background-color: #1e3a8a; color: #fff; font-size: 40px; font-weight: bold;">
                            {{ strtoupper(substr($user->email, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Email</small>
                    <p style="color: #333; margin: 0;">{{ $user->email }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Pet Name</small>
                    <p style="color: #333; margin: 0;">{{ $user->pet_name ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Breed</small>
                    <p style="color: #333; margin: 0;">{{ $user->pet_breed ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Location</small>
                    <p style="color: #333; margin: 0;">{{ $user->city ?? '-' }}, {{ $user->province ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Joined</small>
                    <p style="color: #333; margin: 0;">{{ $user->created_at->format('M d, Y') }}</p>
                </div>

                <div style="margin-bottom: 20px; padding: 12px; background-color: #f9fafb; border-radius: 4px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Certificate Status</small>
                    <p style="margin: 0;">
                        @if($user->certificate_verified)
                            <span class="badge" style="background-color: #059669; color: #fff;">Verified</span>
                        @else
                            <span class="badge" style="background-color: #f59e0b; color: #fff;">Pending</span>
                        @endif
                    </p>
                </div>

                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn" style="background-color: #1e3a8a; color: #fff; border: none; padding: 10px; border-radius: 4px; text-decoration: none; display: block; text-align: center; margin-bottom: 10px; font-weight: 500;">Edit User</a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background-color: #dc2626; color: #fff; border: none; padding: 10px; border-radius: 4px; width: 100%; font-weight: 500; cursor: pointer;" onclick="return confirm('Are you sure?')">Delete User</button>
                </form>
            </div>
        </div>

        <!-- User Activity -->
        <div class="col-lg-8">
            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        <div style="font-size: 24px; color: #1e3a8a; font-weight: bold;">{{ $posts->total() }}</div>
                        <small style="color: #666;">Posts</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        <div style="font-size: 24px; color: #059669; font-weight: bold;">{{ $messages_sent }}</div>
                        <small style="color: #666;">Sent Messages</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        <div style="font-size: 24px; color: #d97706; font-weight: bold;">{{ $messages_received }}</div>
                        <small style="color: #666;">Received Messages</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        <div style="font-size: 24px; color: #9333ea; font-weight: bold;">{{ $user->friends()->count() }}</div>
                        <small style="color: #666;">Friends</small>
                    </div>
                </div>
            </div>

            <!-- Recent Posts -->
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Recent Posts</h5>
                @forelse($posts as $post)
                    <div style="padding: 15px 0; border-bottom: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div>
                                <p style="color: #333; margin: 0 0 4px 0;">{{ Str::limit($post->content, 100) }}</p>
                                <small style="color: #666;">{{ $post->created_at->format('M d, Y H:i') }}</small>
                            </div>
                            <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-sm" style="background-color: #1e3a8a; color: #fff; border: none; font-size: 12px; padding: 4px 8px; border-radius: 4px; text-decoration: none;">View</a>
                        </div>
                    </div>
                @empty
                    <p style="color: #999; text-align: center; padding: 20px 0;">No posts yet</p>
                @endforelse

                @if($posts->hasPages())
                    <div class="mt-3">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
