@extends('navbar.nav')
@section('title', 'Friend Requests - Waggy')
@section('body-class', 'bg-gray-900')

@section('content')

<style>
    @media (max-width: 768px) {
    .friend-title {
        position: relative;
        left:20px; 
        top: 10px;
    }
}
</style>
    <div class="min-h-screen bg-gray-900 text-white p-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 friend-title">Friend Requests</h1>

            @if(session('success'))
                <div class="bg-green-600 text-white p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($requests->count() > 0)
                <ul style="margin: 0; padding: 0; list-style: none;">

                    @foreach($requests as $req)
                        @php
                            $sender = $req->sender;
                        @endphp

                        <li class="bg-gray-800 rounded"
                            style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; margin-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">

                                {{-- Avatar --}}
                                <img src="{{ $sender->avatar ? asset('storage/' . $sender->avatar) : asset('assets/usericon.png') }}"
                                    alt="Avatar" class="rounded-full object-cover border"
                                    style="height: 50px; width: 50px; border-radius: 50px; position:relative; margin-top: 8px;">

                                {{-- Name + Breed (stacked vertically) --}}
                                <div style="display: flex; flex-direction: column;">
                                    <p style="font-size: 1.500rem; font-weight: 700; color: white; margin: 0;">
                                        {{ $sender->pet_name ?? $sender->name }}
                                    </p>

                                    <p style="color: #9ca3af; font-size: 1rem; margin: 0;">
                                        {{ $sender->pet_breed ?? 'Breed not set' }}
                                    </p>
                                </div>

                            </div>

                            {{-- RIGHT BUTTONS --}}
                            <div style="display: flex; gap: 0.5rem; margin-left: auto;">
                                <form action="{{ route('friend.request.accept') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $req->id }}">
                                    <button type="submit"
                                        style="background-color: #3b82f6; padding: 0.5rem 1rem; border-radius: 0.375rem; color: white; font-weight: 500; transition: background-color 0.2s; border: none; cursor: pointer;"
                                        onmouseover="this.style.backgroundColor='#2563eb'"
                                        onmouseout="this.style.backgroundColor='#3b82f6'">
                                        Accept
                                    </button>
                                </form>

                                <form action="{{ route('friend.request.decline') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $req->id }}">
                                    <button type="submit"
                                        style="background-color: #ef4444; padding: 0.5rem 1rem; border-radius: 0.375rem; color: white; font-weight: 500; transition: background-color 0.2s; border: none; cursor: pointer;"
                                        onmouseover="this.style.backgroundColor='#dc2626'"
                                        onmouseout="this.style.backgroundColor='#ef4444'">
                                        Decline
                                    </button>
                                </form>
                            </div>

                        </li>
                    @endforeach

                </ul>
            @else
                <p class="text-gray-400">No friend requests.</p>
            @endif

        </div>
    </div>
@endsection