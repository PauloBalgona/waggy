@extends('admin.layout')
@section('admin-title', 'Certificate Review')

@section('admin-content')
    @if($user->certificate_rejected_at)
        <div style="background-color: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <h6 style="margin: 0 0 8px 0; font-weight: 600;">⚠️ Certificate Rejected</h6>
            <p style="margin: 0; font-size: 14px;">This certificate was rejected on {{ \Carbon\Carbon::parse($user->certificate_rejected_at)->format('M d, Y H:i') }}. User can resubmit a new certificate for verification.</p>
        </div>
    @endif

    <div class="row g-4">
        <!-- Certificate Image -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Certificate Image</h5>
                @if($user->certificate_path)
                    <img src="{{ asset('storage/' . $user->certificate_path) }}" alt="Certificate" class="img-fluid rounded" style="max-width: 100%; max-height: 500px; object-fit: contain; border: 1px solid #e5e7eb;">
                @else
                    <p style="color: #999;">No certificate image available</p>
                @endif
            </div>
        </div>

        <!-- Certificate Info & Actions -->
        <div class="col-lg-6">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); margin-bottom: 25px;">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Pet Information</h5>
                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Pet Name</small>
                    <p style="margin: 0; color: #333;">
                        {{ $user->pet_name ?? '-' }}
                    </p>
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Breed</small>
                    <p style="color: #333; margin: 0;">{{ $user->pet_breed ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Age</small>
                    <p style="color: #333; margin: 0;">{{ $user->pet_age ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Gender</small>
                    <p style="color: #333; margin: 0;">{{ $user->pet_gender ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Color</small>
                    <p style="color: #333; margin: 0;">{{ $user->pet_features ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Owner Email</small>
                    <p style="color: #333; margin: 0;">{{ $user->email }}</p>
                </div>

                <div style="margin-bottom: 20px; padding: 12px; background-color: #f9fafb; border-radius: 4px;">
                    <small style="color: #666; display: block; margin-bottom: 8px;">Status</small>
                    <p style="margin: 0;">
                        @if($user->certificate_verified)
                            <span class="badge" style="background-color: #059669; color: #fff;">Verified</span>
                        @elseif($user->certificate_rejected_at)
                            <span class="badge" style="background-color: #dc2626; color: #fff;">Rejected</span>
                        @else
                            <span class="badge" style="background-color: #f59e0b; color: #fff;">Pending Review</span>
                        @endif
                    </p>
                </div>
            </div>

            @if(!$user->certificate_verified && !$user->certificate_rejected_at)
                <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                    <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Actions</h5>

                    <form action="{{ route('admin.certificates.verify', $user->id) }}" method="POST" style="margin-bottom: 10px;">
                        @csrf
                        <button type="submit" class="btn" style="background-color: #059669; color: #fff; border: none; padding: 10px; border-radius: 4px; width: 100%; font-weight: 500; cursor: pointer; margin-bottom: 10px;" onclick="return confirm('Verify this certificate?')">Verify Certificate</button>
                    </form>

                    <form action="{{ route('admin.certificates.reject', $user->id) }}" method="POST" style="margin-bottom: 0;">
                        @csrf
                        <button type="submit" class="btn" style="background-color: #dc2626; color: #fff; border: none; padding: 10px; border-radius: 4px; width: 100%; font-weight: 500; cursor: pointer;" onclick="return confirm('Reject this certificate?')">Reject Certificate</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
