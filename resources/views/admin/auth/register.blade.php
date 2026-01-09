@extends('layouts.app')
@section('title', 'Admin Registration - Waggy')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#f5f5f7] px-6">
    <div class="bg-white rounded-2xl shadow-md p-10 w-full max-w-sm">
        <h2 class="text-3xl font-bold text-center mb-8 text-blue-600">Admin Registration</h2>

        @php
            $adminExists = \App\Models\User::where('is_admin', true)->exists();
        @endphp

        @if($adminExists && !auth()->check())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <strong>Registration Closed</strong>
            <p class="mt-2">Admin registration is only available during initial setup. Please contact an existing admin to create new admin accounts.</p>
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('admin.login') }}" class="text-blue-600 hover:underline font-medium">Back to Admin Login</a>
        </div>
        @else
        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form class="space-y-5" action="{{ route('admin.register.post') }}" method="POST">
            @csrf

            <div>
                <label for="name" class="block text-gray-600 mb-1 text-sm font-medium">Full Name</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
                       value="{{ old('name') }}">
            </div>

            <div>
                <label for="email" class="block text-gray-600 mb-1 text-sm font-medium">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
                       placeholder="Enter your email" value="{{ old('email') }}">
            </div>

            <div>
                <label for="password" class="block text-gray-600 mb-1 text-sm font-medium">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
                       placeholder="Create a password">
            </div>

            <div>
                <label for="password_confirmation" class="block text-gray-600 mb-1 text-sm font-medium">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
                       placeholder="Confirm your password">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                Create Admin Account
            </button>
        </form>

        <div class="mt-6 text-center border-t pt-6">
            <p class="text-gray-600 text-sm">
                Already have an account?
                <a href="{{ route('admin.login') }}" class="text-blue-600 hover:underline font-medium">Admin Login</a>
            </p>
            <p class="text-gray-600 text-sm mt-2">
                <a href="{{ route('landing') }}" class="text-blue-600 hover:underline">Back to Home</a>
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
