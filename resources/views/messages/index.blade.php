@extends('navbar.nav2')
@section('title', 'Comments')
@section('body-class', 'bg-gray-900')

@section('content')
    <div class="flex h-screen bg-[#0f141a] text-white">

        <!-- LEFT SIDE (Conversation List) -->
        <div class="w-80 border-r border-gray-700 p-4">

            <h2 class="text-2xl font-bold mb-4">Message</h2>

            <!-- Search -->
            <input type="text" placeholder="Search conversations..."
                class="w-full bg-[#1c1f24] text-white rounded-full px-4 py-2 mb-4 outline-none">

            <!-- Placeholder Conversation Items -->
            <div class="flex items-center gap-3 p-3 hover:bg-[#1c1f24] rounded-xl cursor-pointer">
                <img src="https://via.placeholder.com/50" class="w-12 h-12 rounded-full object-cover">
                <div>
                    <p class="font-semibold">Bella Golden Retriever</p>
                    <p class="text-sm text-gray-400">You: Yes! This Weekend :)</p>
                </div>
                <span class="ml-auto text-xs text-gray-400">1:30PM</span>
            </div>

            <div class="flex items-center gap-3 p-3 hover:bg-[#1c1f24] rounded-xl cursor-pointer">
                <img src="https://via.placeholder.com/50" class="w-12 h-12 rounded-full object-cover">
                <div>
                    <p class="font-semibold">Buddy Labrador Retriever</p>
                    <p class="text-sm text-gray-400">You: Deal! don't back out ha</p>
                </div>
                <span class="ml-auto text-xs text-gray-400">1:30PM</span>
            </div>

            <div class="flex items-center gap-3 p-3 hover:bg-[#1c1f24] rounded-xl cursor-pointer">
                <img src="https://via.placeholder.com/50" class="w-12 h-12 rounded-full object-cover">
                <div>
                    <p class="font-semibold">Charlie Siberian Husky</p>
                    <p class="text-sm text-gray-400">You: I'll be looking forward to it</p>
                </div>
                <span class="ml-auto text-xs text-gray-400">1:30PM</span>
            </div>

        </div>

        <!-- RIGHT SIDE (Chat Window) -->
        <div class="flex-1 flex flex-col">

            <!-- Header -->
            <div class="p-4 border-b border-gray-700 flex items-center gap-3">
                <img src="https://via.placeholder.com/50" class="w-12 h-12 rounded-full object-cover">

                <div>
                    <p class="text-lg font-bold">Buddy</p>
                    <p class="text-sm text-gray-400">Labrador Retriever</p>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 p-6 space-y-4 overflow-y-auto">

                <!-- Message Left -->
                <div class="flex">
                    <div class="bg-[#1c1f24] px-4 py-2 rounded-xl text-white">
                        Want to go to the park?
                    </div>
                </div>

                <!-- Message Right -->
                <div class="flex justify-end">
                    <div class="bg-blue-600 px-4 py-2 rounded-xl text-white">
                        Deal! don't back out ha
                    </div>
                </div>

            </div>

            <!-- Input Area -->
            <div class="p-4 border-t border-gray-700 flex items-center gap-3">
                <input type="text" placeholder="Type a message..."
                    class="flex-1 bg-[#1c1f24] px-4 py-3 rounded-full text-white outline-none">

                <button class="bg-blue-600 px-6 py-3 rounded-full font-semibold">
                    Send
                </button>
            </div>

        </div>

    </div>
@endsection