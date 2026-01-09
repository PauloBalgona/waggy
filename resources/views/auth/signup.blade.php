@extends('layouts.app')
@section('title', 'Sign Up - Waggy')

@section('content')
<div class="min-h-screen flex items-start justify-center bg-[#f5f5f7] px-4 pt-16 relative">

  <style>
    /* Placeholder */
    #register-form input::placeholder,
    #register-form textarea::placeholder {
      color: #9aa4b2;
      opacity: 1;
      font-size: 0.875rem;
    }

    #register-form select:invalid {
      color: #9aa4b2;
      font-size: 0.875rem;
    }

    #register-form select {
      color: #111827;
      font-size: 0.875rem;
    }

    /* Uniform gray borders */
    #register-form input,
    #register-form textarea,
    #register-form select {
      border: 1px solid #9ca3af; /* gray-400 */
    }

    #uploadArea {
      border: 1px solid #9ca3af;
    }

    /* Focus */
    #register-form input:focus,
    #register-form textarea:focus,
    #register-form select:focus {
      border-color: #3b82f6;
      outline: none;
    }
  </style>

  {{-- Back --}}
  <a href="{{ route('landing') }}"
     class="absolute top-4 right-4 text-blue-600 text-xs font-medium">
    Back to Waggy
  </a>

  {{-- FORM CARD --}}
  <div class="w-full flex justify-center">
    <div class="bg-white rounded-xl shadow-md w-full max-w-sm p-5">

      <p class="text-gray-400 text-xs mb-3 text-center">
        Automatically scan and validate uploaded pet documents
      </p>

      @if($errors->any())
      <div class="mb-3 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-xs">
        @foreach($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
      @endif

      <form id="register-form" class="space-y-3"
            action="{{ route('signup.post') }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf

        {{-- Email --}}
        <input type="email" name="email" required
          pattern="[A-Za-z0-9._%+\-]+@gmail\.com"
          class="w-full px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
          placeholder="Email (@gmail.com)">

        {{-- Password --}}
        <input type="password" name="password" required
          class="w-full px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
          placeholder="Password">

        {{-- Pet Name + Breed --}}
        <div class="flex gap-2">
          <input type="text" name="pet_name" required
            class="w-1/2 px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
            placeholder="Pet Name">

          <div class="relative w-1/2">
            <input type="text" id="breedInput" name="breedtype" required autocomplete="off"
              class="w-full px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
              placeholder="Breed">
            <ul id="breedList"
              class="absolute bg-white w-full shadow-md rounded-md hidden max-h-32 overflow-y-auto text-xs z-50"></ul>
          </div>
        </div>

        {{-- Age + Gender --}}
        <div class="flex gap-2">
          <input type="number" name="dog_age" required min="0" max="30"
            class="w-1/2 px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
            placeholder="Dog Age">

          <select name="gendertype" id="gendertype" required
            class="w-1/2 px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="" disabled selected>Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>

        {{-- Features --}}
        <textarea name="features" rows="2"
          class="w-full px-3 py-2 rounded-lg text-sm resize-none focus:ring-2 focus:ring-blue-500"
          placeholder="Distinguished features"></textarea>

        {{-- Upload --}}
        <div id="uploadArea"
          class="rounded-lg py-4 px-3 text-center text-gray-500 text-xs">
          <p class="font-medium">Drag & drop certificate</p>
          <p class="text-gray-400">PDF, JPG, PNG (2MB)</p>

          <button type="button" id="uploadBtn"
            class="mt-2 bg-green-600 text-white px-4 py-1.5 rounded text-xs">
            Upload
          </button>

          <div id="fileName" class="mt-2 text-gray-700"></div>
          <div id="fileError" class="mt-1 text-red-600 font-medium"></div>
        </div>

        <input type="file" id="fileInput" name="certificate"
               accept=".pdf,.jpg,.jpeg,.png" class="hidden">

        {{-- Submit --}}
        <button type="submit"
          class="w-full bg-blue-600 text-white rounded-lg py-2.5 text-sm font-semibold hover:bg-blue-700">
          Register
        </button>

        <p class="text-center text-gray-500 text-xs pt-2">
          Already have an account?
          <a href="{{ route('login') }}" class="text-blue-600 font-medium">Log In</a>
        </p>
      </form>
    </div>
  </div>
</div>

{{-- JS --}}
<script>
const breeds = ["Shih Tzu","Pomeranian","Labrador Retriever","Golden Retriever","Siberian Husky","Beagle","Pug","Chihuahua","French Bulldog","American Bulldog","German Shepherd","Belgian Malinois","American Bully","Doberman Pinscher","Rottweiler","Border Collie"];
const breedInput = document.getElementById("breedInput");
const breedList = document.getElementById("breedList");

breedInput.addEventListener("input", () => {
  const value = breedInput.value.toLowerCase();
  breedList.innerHTML = "";
  if (!value) return breedList.classList.add("hidden");

  breeds.filter(b => b.toLowerCase().includes(value)).forEach(b => {
    const li = document.createElement("li");
    li.textContent = b;
    li.className = "px-3 py-2 cursor-pointer hover:bg-gray-100";
    li.onclick = () => {
      breedInput.value = b;
      breedList.classList.add("hidden");
    };
    breedList.appendChild(li);
  });

  breedList.classList.remove("hidden");
});

const fileInput = document.getElementById("fileInput");
const uploadBtn = document.getElementById("uploadBtn");
const fileName = document.getElementById("fileName");
const fileError = document.getElementById("fileError");

uploadBtn.onclick = () => fileInput.click();
fileInput.onchange = () => {
  const file = fileInput.files[0];
  fileError.textContent = "";
  if (file && file.size > 2 * 1024 * 1024) {
    fileError.textContent = "File too large (2MB max)";
    fileInput.value = "";
    return;
  }
  if (file) fileName.textContent = file.name;
};
</script>
@endsection
