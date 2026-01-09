@extends('layouts.app')
@section('title', 'Admin Login - Waggy')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#f5f5f7] px-6">
    <div class="bg-white rounded-2xl shadow-md p-10 w-full max-w-sm">
        <h2 class="text-3xl font-bold text-center mb-8 text-blue-600">Admin Portal</h2>

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form class="space-y-5" action="{{ route('admin.login.post') }}" method="POST">
            @csrf

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
                       placeholder="Enter your password">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                Login as Admin
            </button>
        </form>

        <div class="mt-6 text-center border-t pt-6">
            <p class="text-gray-600 text-sm mb-2">Don't have an admin account?</p>
            <a href="{{ route('admin.register') }}" class="text-blue-600 hover:underline font-medium">Admin Registration</a>
            <p class="text-gray-600 text-sm mt-3">
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">User Login</a>
            </p>
            <p class="text-gray-600 text-sm mt-2">
                <a href="{{ route('landing') }}" class="text-blue-600 hover:underline">Back to Home</a>
            </p>
        </div>
    </div>
</div>
@endsection
