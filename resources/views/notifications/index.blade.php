@extends('navbar.nav1')
@section('title', 'Notifications - Waggy')
@section('body-class', 'bg-gray-900')

@section('content')
    <div class="min-h-screen bg-gray-900 text-white p-8">
        <h1 class="text-3xl font-bold mb-6">Notifications</h1>

        <div class="space-y-4">
            @forelse($notifications as $notification)
                @php
                    $actor = $notification->actor; 
                @endphp

                <div class="bg-gray-800 rounded-lg p-2 flex items-start gap-2 {{ $notification->is_read ? 'opacity-60' : '' }}">

                    <img src="{{ $actor && $actor->avatar ? asset('storage/' . $actor->avatar) : asset('assets/usericon.png') }}"
                        alt="Avatar" class="rounded-lg object-cover border border-gray-600"
                        style="height:50px; width:50px; flex-shrink:0; position: relative; right: 55px; top: 40px; margin-left: 30px; border-radius: 50px;">

                    <div class="flex-1" style="position: relative; margin-left: 50px; bottom: 10px;">

                        @if($actor)
                            <p class="m-0 leading-snug text-sm">
                                <span class="font-bold">
                                    {{ $actor->pet_name ?? $actor->name }}
                                </span>
                                <span class="text-gray-400">
                                    · {{ $actor->pet_breed ?? 'Breed not set' }}
                                </span>
                                {{ $notification->message }}
                            </p>

                            <!-- Time -->
                            <p class="text-xs text-gray-400 mt-1 mb-0">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        @endif
                    </div>
                </div>

            @empty
                <p class="text-gray-400">No notifications found</p>
            @endforelse
        </div>
    </div>
@endsection