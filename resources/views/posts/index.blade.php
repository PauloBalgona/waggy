@extends('navbar.nav2')
@section('title', 'Create Dog Post - Waggy')
@section('body-class', 'bg-gray-900')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100 p-3">
        <div class="position-relative rounded-3 p-4" style="background-color: #252938; max-width: 450px; width: 100%;">


            {{-- Close Button --}}
            <a href="{{ route('home') }}"
                class="position-absolute top-0 start-0 m-3 btn btn-link text-decoration-none p-0 d-flex align-items-center justify-content-center"
                style="color: #8b92a7; font-size: 20px; width: 30px; height: 30px; z-index: 10;"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#8b92a7'">
                ✕
            </a>

            {{-- Send Button --}}
            <button class="position-absolute top-0 end-0 m-3 btn btn-primary rounded-2 px-4 py-2"
                style="background-color: #4c6ef5; border: none; font-size: 14px; font-weight: 500; z-index: 10;"
                onclick="submitForm()" onmouseover="this.style.backgroundColor='#5c7cfa'"
                onmouseout="this.style.backgroundColor='#4c6ef5'">
                Send
            </button>

            {{-- Upload --}}
            <div class="text-center mt-5 mb-4">
                <label class="d-block mb-3" style="font-size: 13px; color: #8b92a7;">Upload Your dog photo</label>
                <label for="photoUpload" class="d-inline-flex align-items-center justify-content-center rounded-2"
                    id="photoPreview"
                    style="width: 100%; max-width: 395px; height: 200px; background: linear-gradient(135deg, #7ba5f5 0%, #5c8ff5 100%); cursor: pointer; transition: 0.3s; position: relative; overflow: hidden;">
                    <img id="previewImg" src="{{ session('uploaded_image') ?? '' }}"
                        style="width:100%; height:100%; object-fit:contain; border-radius:10px; display: {{ session('uploaded_image') ? 'block' : 'none' }};">
                    <span id="photoIcon"
                        style="font-size: 48px; color: rgba(255,255,255,0.8); display: {{ session('uploaded_image') ? 'none' : 'block' }}; position: absolute;">🏔️</span>
                </label>
                <input type="file" id="photoUpload" accept="image/*" class="d-none" onchange="previewImage(event)">
            </div>

            {{-- Form --}}
            <div>
                <label class="d-block mb-3" style="font-size: 13px; color: #8b92a7;">What's on your mind?</label>
                <textarea id="messageTextArea" class="form-control rounded-2 mb-3" rows="3" placeholder="Write something..."
                    style="background-color: #1e2230; border: 1px solid #3a3f52; color: #fff; padding: 12px 16px; font-size: 14px; resize: none;"></textarea>

                {{-- Age / Breed --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <select id="selectAge" class="form-select rounded-2"
                            style="background-color: #1e2230; border: 1px solid #3a3f52; color: #fff; padding: 12px 16px; font-size: 14px;">
                            <option value="">Select Age</option>
                            <option value="1">1 year</option>
                            <option value="2">2 years</option>
                            <option value="3">3 years</option>
                            <option value="4">4 years</option>
                            <option value="5">5 years</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <select id="selectBreed" class="form-select rounded-2"
                            style="background-color: #1e2230; border: 1px solid #3a3f52; color: #fff; padding: 12px 16px; font-size: 14px;">
                            <option value="">Select Breed</option>
                            <option value="Labrador">Labrador</option>
                            <option value="Golden Retriever">Golden Retriever</option>
                            <option value="Pug">Pug</option>
                            <option value="Shih Tzu">Shih Tzu</option>
                            <option value="Pomeranian">Pomeranian</option>
                        </select>
                    </div>
                </div>

                {{-- Province / City --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <select class="form-select rounded-2" id="selectProvince"
                            style="background-color: #1e2230; border: 1px solid #3a3f52; color: #fff; padding: 12px 16px; font-size: 14px;">
                            <option value="">Select Province</option>
                            <option value="Pampanga">Pampanga</option>
                            <option value="Cavite">Cavite</option>
                            <option value="Laguna">Laguna</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <select class="form-select rounded-2" id="selectCity"
                            style="background-color: #1e2230; border: 1px solid #3a3f52; color: #fff; padding: 12px 16px; font-size: 14px;"
                            disabled>
                            <option value="">Select City</option>
                        </select>
                    </div>
                </div>

                {{-- Interest / Audience --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <select class="form-select rounded-2" id="selectInterest"
                            style="background-color: #1e2230; border: 1px solid #3a3f52; color: #fff; padding: 12px 16px; font-size: 14px;">
                            <option value="">Interest</option>
                            <option>Breeding</option>
                            <option>Playdate</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <select class="form-select rounded-2" id="selectAudience"
                            style="background-color: #1e2230; border: 1px solid #3a3f52; color: #fff; padding: 12px 16px; font-size: 14px;">
                            <option value="">Audience</option>
                            <option value="public">Public</option>
                            <option value="friends">Friends</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image preview
        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;
            const previewImg = document.getElementById("previewImg");
            const icon = document.getElementById("photoIcon");
            previewImg.src = URL.createObjectURL(file);
            previewImg.style.display = "block";
            icon.style.display = "none";
        }

        // Province → City 
        const locations = {
            "Pampanga": ["Angeles City", "Mabalacat City", "San Fernando City", "Mexico", "Bacolor", "Guagua", "Porac", "Santa Rita", "Magalang"],
            "Cavite": ["Bacoor City", "Imus City", "Dasmariñas City", "Tagaytay City", "General Trias", "Trece Martires City", "Kawit", "Rosario", "Silang", "Tanza"],
            "Laguna": ["Calamba City", "Santa Rosa City", "Biñan City", "San Pedro City", "Cabuyao City", "San Pablo City", "Los Baños", "Pagsanjan", "Sta. Cruz", "Bay"]
        };
        const selectProvince = document.getElementById("selectProvince");
        const selectCity = document.getElementById("selectCity");

        selectProvince.addEventListener("change", function () {
            const province = this.value;
            selectCity.innerHTML = '<option value="">Select City</option>';
            if (!province) { selectCity.disabled = true; return; }
            locations[province].forEach(city => {
                const option = document.createElement("option");
                option.value = city;
                option.textContent = city;
                selectCity.appendChild(option);
            });
            selectCity.disabled = false;
        });

        // Submit form
        function submitForm() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('posts.store') }}";
            form.enctype = "multipart/form-data";
            form.innerHTML += `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
            form.innerHTML += `<input type="hidden" name="content" value="${document.getElementById('messageTextArea').value}">`;
            form.innerHTML += `<input type="hidden" name="age" value="${document.getElementById('selectAge').value}">`;
            form.innerHTML += `<input type="hidden" name="breed" value="${document.getElementById('selectBreed').value}">`;
            form.innerHTML += `<input type="hidden" name="province" value="${selectProvince.value || ''}">`;
            form.innerHTML += `<input type="hidden" name="city" value="${selectCity.value || ''}">`;
            form.innerHTML += `<input type="hidden" name="interest" value="${document.getElementById('selectInterest').value}">`;
            form.innerHTML += `<input type="hidden" name="audience" value="${document.getElementById('selectAudience').value}">`;

            const fileInput = document.getElementById('photoUpload');
            if (fileInput.files[0]) {
                const dt = new DataTransfer();
                dt.items.add(fileInput.files[0]);
                const newInput = document.createElement('input');
                newInput.type = 'file';
                newInput.name = 'photoUpload';
                newInput.files = dt.files;
                form.appendChild(newInput);
            } else if ("{{ session('uploaded_image') }}") {
                form.innerHTML += `<input type="hidden" name="image_base64" value="{{ session('uploaded_image') }}">`;
            }

            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endsection