@extends('admin.layout')
@section('admin-title', 'Users Management')

@section('admin-content')
    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.users') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search users by name, email, or pet..." value="{{ request('search') }}" style="border: 1px solid #d1d5db; border-radius: 4px 0 0 4px;">
                <button class="btn" type="submit" style="background-color: #1e3a8a; color: #fff; border: none; border-radius: 0 4px 4px 0;">Search</button>
            </div>
        </form>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table" style="color: #333;">
                <thead style="background-color: #f9fafb; border-color: #e5e7eb;">
                    <tr>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Email</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Pet</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Location</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Status</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px; color: #666;">{{ $user->email }}</td>
                            <td style="padding: 12px; color: #666;">{{ $user->pet_name ?? '-' }}</td>
                            <td style="padding: 12px; color: #666;">{{ $user->city ?? '-' }}, {{ $user->province ?? '-' }}</td>
                            <td style="padding: 12px;">
                                @if($user->is_online)
                                    <span class="badge" style="background-color: #059669; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px;">Online</span>
                                @else
                                    <span class="badge" style="background-color: #9ca3af; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px;">Offline</span>
                                @endif
                            </td>
                            <td style="padding: 12px;">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm" style="background-color: #1e3a8a; color: #fff; border: none; font-size: 12px; padding: 4px 8px; border-radius: 4px; text-decoration: none; margin-right: 4px;">View</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block; margin-left: 6px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #dc2626; color: #fff; border: none; font-size: 12px; padding: 4px 8px; border-radius: 4px;" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #999; padding: 30px;">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
