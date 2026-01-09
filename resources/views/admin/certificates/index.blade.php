@extends('admin.layout')
@section('admin-title', 'Certificates Management')

@section('admin-content')
    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <!-- Filter Tabs -->
        <div style="margin-bottom: 20px; display: flex; gap: 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px;">
            <a href="{{ route('admin.certificates', ['status' => 'pending']) }}" style="padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: 500; @if($status === 'pending') background-color: #1e3a8a; color: #fff; @else background-color: #f3f4f6; color: #333; @endif">
                Pending
            </a>
            <a href="{{ route('admin.certificates', ['status' => 'verified']) }}" style="padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: 500; @if($status === 'verified') background-color: #1e3a8a; color: #fff; @else background-color: #f3f4f6; color: #333; @endif">
                Verified
            </a>
            <a href="{{ route('admin.certificates', ['status' => 'rejected']) }}" style="padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: 500; @if($status === 'rejected') background-color: #991b1b; color: #fff; @else background-color: #f3f4f6; color: #333; @endif">
                Rejected
            </a>
        </div>

        <!-- Certificates Table -->
        <div class="table-responsive">
            <table class="table" style="color: #333;">
                <thead style="background-color: #f9fafb; border-color: #e5e7eb;">
                    <tr>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Email</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Status</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Submitted</th>
                        <th style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e7eb; padding: 12px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $user)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px; color: #666;">{{ $user->email }}</td>
                            <td style="padding: 12px;">
                                @if($user->certificate_verified)
                                    <span class="badge" style="background-color: #059669; color: #fff;">Verified</span>
                                @elseif($user->certificate_rejected_at)
                                    <span class="badge" style="background-color: #dc2626; color: #fff;">Rejected</span>
                                @else
                                    <span class="badge" style="background-color: #f59e0b; color: #fff;">Pending</span>
                                @endif
                            </td>
                            <td style="padding: 12px; color: #666;">{{ $user->created_at->format('M d, Y') }}</td>
                            <td style="padding: 12px;">
                                <a href="{{ route('admin.certificates.show', $user->id) }}" class="btn btn-sm" style="background-color: #1e3a8a; color: #fff; border: none; font-size: 12px; padding: 4px 8px; border-radius: 4px; text-decoration: none;">View</a>
                                @if($status === 'rejected')
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: #dc2626; color: #fff; border: none; font-size: 12px; padding: 4px 8px; border-radius: 4px; cursor: pointer;" onclick="return confirm('Delete this rejected certificate?')">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999; padding: 30px;">No certificates found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $certificates->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
