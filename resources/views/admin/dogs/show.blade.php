@extends('admin.layout')
@section('admin-title', 'Pet Details')

@section('admin-content')
    <div class="row g-4">
        <!-- Pet Info -->
        <div class="col-lg-4">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <div class="text-center mb-4">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->pet_name }}" class="rounded" style="width: 100%; height: 200px; object-fit: cover; border: 1px solid #e5e7eb;">
                    @else
                        <div style="width: 100%; height: 200px; background-color: #f9fafb; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 80px; border: 1px solid #e5e7eb;">
                            🐕
                        </div>
                    @endif
                </div>

                <h4 style="color: #1e3a8a; text-align: center; margin-bottom: 20px; font-weight: 700; font-size: 24px;">{{ $user->pet_name ?? 'Unknown' }}</h4>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Breed</small>
                    <p style="color: #333; margin: 0; font-weight: 500;">{{ $user->pet_breed ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Age</small>
                    <p style="color: #333; margin: 0; font-weight: 500;">{{ $user->pet_age ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Gender</small>
                    <p style="color: #333; margin: 0; font-weight: 500;">{{ $user->pet_gender ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 20px; padding: 12px; background-color: #f9fafb; border-radius: 4px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Certificate Status</small>
                    <p style="margin: 0;">
                        @if($user->certificate_verified)
                            <span class="badge" style="background-color: #059669; color: #fff;">Verified</span>
                        @elseif($user->certificate_rejected_at)
                            <span class="badge" style="background-color: #dc2626; color: #fff;">Rejected</span>
                        @else
                            <span class="badge" style="background-color: #f59e0b; color: #fff;">Pending</span>
                        @endif
                    </p>
                </div>

                <div style="margin-bottom: 20px; padding: 12px; background-color: #f9fafb; border-radius: 4px;">
                    <small style="color: #666; display: block; margin-bottom: 4px;">Owner</small>
                    <a href="{{ route('admin.users.show', $user->id) }}" style="text-decoration: none; color: #1e3a8a; font-weight: 500;">
                        {{ $user->email }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Dog Details -->
        <div class="col-lg-8">
            <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Dog Information</h5>

                <div style="margin-bottom: 20px;">
                    <small style="color: #666; display: block; margin-bottom: 8px;">Pet Name</small>
                    <p style="color: #333; line-height: 1.6;">{{ $user->pet_name ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <small style="color: #666; display: block; margin-bottom: 8px;">Age</small>
                    <p style="color: #333; line-height: 1.6;">{{ $user->pet_age ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <small style="color: #666; display: block; margin-bottom: 8px;">Breed</small>
                    <p style="color: #333; line-height: 1.6;">{{ $user->pet_breed ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <small style="color: #666; display: block; margin-bottom: 8px;">Gender</small>
                    <p style="color: #333; line-height: 1.6;">{{ $user->pet_gender ?? '-' }}</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <small style="color: #666; display: block; margin-bottom: 8px;">Color</small>
                    <p style="color: #333; line-height: 1.6;">{{ $user->pet_features ?? '-' }}</p>
                </div>

                <hr style="border-color: #e5e7eb; margin: 20px 0;">

                <h5 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 600;">Owner Information</h5>
                <div style="margin-bottom: 20px;">
                    <small style="color: #666; display: block; margin-bottom: 8px;">Email</small>
                    <p style="color: #333; line-height: 1.6;">{{ $user->email }}</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <small style="color: #666; display: block; margin-bottom: 8px;">Location</small>
                    <p style="color: #333; line-height: 1.6;">{{ $user->city ?? '-' }}, {{ $user->province ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
