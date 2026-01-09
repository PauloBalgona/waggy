@extends('layouts.app')
@section('title', 'Login - Waggy')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#f5f5f7] px-4 relative">

  {{-- Back to Waggy --}}
  <a href="{{ route('landing') }}"
     class="absolute top-6 right-6 text-blue-600 hover:text-blue-700 text-xs sm:text-sm font-medium">
    Back to Waggy
  </a>

  {{-- Login Card --}}
  <div class="bg-white rounded-2xl shadow-md p-6 sm:p-8 w-full max-w-xs sm:max-w-sm">

    {{-- Errors --}}
    @if($errors->any())
      <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-xs">
        {{ $errors->first() }}
      </div>
    @endif

    <form class="space-y-3 sm:space-y-4" action="{{ route('login.post') }}" method="POST">
      @csrf

      {{-- Email --}}
      <div>
        <input
          type="email"
          name="email"
          required
          value="{{ old('email') }}"
          placeholder="Email address"
          class="w-full px-3 py-2 border border-gray-300 rounded-lg
                 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
        >
      </div>

      {{-- Password --}}
      <div>
        <input
          type="password"
          name="password"
          required
          placeholder="Password"
          class="w-full px-3 py-2 border border-gray-300 rounded-lg
                 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
        >
      </div>

      {{-- Forgot --}}
      <div class="flex justify-end">
        <a href="#" class="text-[11px] text-blue-600 hover:underline">
          Forgot password?
        </a>
      </div>

      {{-- Button --}}
      <button
        type="submit"
        class="w-full bg-blue-600 text-white font-semibold
               rounded-lg py-2 text-sm hover:bg-blue-700 transition">
        Login
      </button>

      {{-- Signup --}}
      <p class="text-center text-gray-500 text-[11px] pt-1">
        Don’t have an account?
        <a href="{{ route('signup') }}" class="text-blue-600 font-medium hover:underline">
          Sign up
        </a>
      </p>

      {{-- Admin --}}
      <div class="mt-4 text-center border-t pt-3">
        <p class="text-gray-500 text-[10px] mb-1">Are you an admin?</p>
        <a href="{{ route('admin.login') }}" class="text-blue-600 text-xs hover:underline font-medium">
          Admin Login
        </a>
      </div>

    </form>
  </div>
</div>
@endsection
