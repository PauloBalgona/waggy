@extends('admin.layout')
@section('admin-title', 'Posts Management')

@section('admin-content')
    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.posts') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search posts by content or author..." value="{{ request('search') }}" style="border: 1px solid #d1d5db; border-radius: 4px 0 0 4px;">
                <button class="btn" type="submit" style="background-color: #1e3a8a; color: #fff; border: none; border-radius: 0 4px 4px 0;">Search</button>
            </div>
        </form>

        <!-- Posts Table -->
        <div class="table-responsive">
            <table class="table" style="color: #333;">
                <thead style="background-color: #f9fafb; border-color: #e5e7eb;">
                    <tr>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Author</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Content</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Likes</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Posted</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px; color: #333;">{{ $post->user->email }}</td>
                            <td style="padding: 12px; color: #666;">{{ Str::limit($post->message, 60) }}</td>
                            <td style="padding: 12px; color: #333; font-weight: 500;">{{ $post->likes()->count() }}</td>
                            <td style="padding: 12px; color: #666;">{{ $post->created_at->format('M d, Y') }}</td>
                            <td style="padding: 12px;">
                                <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-sm" style="background-color: #1e3a8a; color: #fff; border: none; font-size: 12px; padding: 4px 8px; border-radius: 4px; text-decoration: none; margin-right: 4px;">View</a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #dc2626; color: #fff; border: none; font-size: 12px; padding: 4px 8px; border-radius: 4px; cursor: pointer;" onclick="return confirm('Delete this post?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #999; padding: 30px;">No posts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
