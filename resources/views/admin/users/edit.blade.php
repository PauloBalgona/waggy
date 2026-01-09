@extends('admin.layout')
@section('admin-title', 'Edit User - ' . $user->name)

@section('admin-content')
    <div style="background: #fff; border: 1px solid #e5e7eb; padding: 25px; border-radius: 8px; max-width: 600px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
        <h3 style="color: #1e3a8a; margin-bottom: 20px; font-weight: 700;">Edit User - {{ $user->name }}</h3>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; color: #333; margin-bottom: 6px; font-weight: 500;">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 12px; background: #fff; color: #333;">
                @error('name')<span style="color: #dc2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; color: #333; margin-bottom: 6px; font-weight: 500;">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 12px; background: #fff; color: #333;">
                @error('email')<span style="color: #dc2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="pet_name" style="display: block; color: #333; margin-bottom: 6px; font-weight: 500;">Pet Name</label>
                <input type="text" class="form-control @error('pet_name') is-invalid @enderror" id="pet_name" name="pet_name" value="{{ $user->pet_name }}" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 12px; background: #fff; color: #333;">
                @error('pet_name')<span style="color: #dc2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="pet_breed" style="display: block; color: #333; margin-bottom: 6px; font-weight: 500;">Pet Breed</label>
                <input type="text" class="form-control @error('pet_breed') is-invalid @enderror" id="pet_breed" name="pet_breed" value="{{ $user->pet_breed }}" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 12px; background: #fff; color: #333;">
                @error('pet_breed')<span style="color: #dc2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="pet_age" style="display: block; color: #333; margin-bottom: 6px; font-weight: 500;">Pet Age</label>
                <input type="number" class="form-control @error('pet_age') is-invalid @enderror" id="pet_age" name="pet_age" value="{{ $user->pet_age }}" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 12px; background: #fff; color: #333;">
                @error('pet_age')<span style="color: #dc2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="city" style="display: block; color: #333; margin-bottom: 6px; font-weight: 500;">City</label>
                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ $user->city }}" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 12px; background: #fff; color: #333;">
                @error('city')<span style="color: #dc2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="province" style="display: block; color: #333; margin-bottom: 6px; font-weight: 500;">Province</label>
                <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ $user->province }}" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 12px; background: #fff; color: #333;">
                @error('province')<span style="color: #dc2626; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 25px; padding: 15px; background-color: #f9fafb; border-radius: 4px; border: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: center;">
                    <input class="form-check-input" type="checkbox" id="certificate_verified" name="certificate_verified" value="1" @if($user->certificate_verified) checked @endif style="width: 18px; height: 18px; cursor: pointer; margin-right: 10px;">
                    <label style="color: #333; font-weight: 500; margin: 0; cursor: pointer;" for="certificate_verified">
                        Certificate Verified
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn" style="background-color: #1e3a8a; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 500; cursor: pointer;">Save Changes</button>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn" style="background-color: #6b7280; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 500;">Cancel</a>
            </div>
        </form>
    </div>
@endsection
