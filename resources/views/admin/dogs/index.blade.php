@extends('admin.layout')
@section('admin-title', 'Dogs Management')

@section('admin-content')
    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.dogs') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by pet name, breed, or email..." value="{{ request('search') }}" style="border: 1px solid #d1d5db; border-radius: 4px 0 0 4px;">
                <button class="btn" type="submit" style="background-color: #1e3a8a; color: #fff; border: none; border-radius: 0 4px 4px 0;">Search</button>
            </div>
        </form>

        <!-- Dogs Table -->
        <div class="table-responsive">
            <table class="table" style="color: #333;">
                <thead style="background-color: #f9fafb; border-color: #e5e7eb;">
                    <tr>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Pet Name</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Breed</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Owner Email</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Age</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Location</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dogs as $user)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px; color: #333; font-weight: 500;">{{ $user->pet_name ?? '-' }}</td>
                            <td style="padding: 12px; color: #666;">{{ $user->pet_breed ?? '-' }}</td>
                            <td style="padding: 12px; color: #666;">{{ $user->email ?? '-' }}</td>
                            <td style="padding: 12px; color: #666;">{{ $user->pet_age ?? '-' }}</td>
                            <td style="padding: 12px; color: #666;">{{ $user->city ?? '-' }}, {{ $user->province ?? '-' }}</td>
                            <td style="padding: 12px;">
                                <a href="{{ route('admin.dogs.show', $user->id) }}" class="btn btn-sm" style="background-color: #1e3a8a; color: #fff; border: none; font-size: 12px; padding: 4px 8px; border-radius: 4px; text-decoration: none;">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999; padding: 30px;">No dogs found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $dogs->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
