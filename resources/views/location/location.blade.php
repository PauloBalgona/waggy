@extends('navbar.nav')
@section('title', 'Location - Waggy')
@section('body-class', 'bg-[#191B21]')

@section('content')
    <style>
        #mainContent::-webkit-scrollbar {
            width: 0px;
        }

        .location-item {
            transition: background-color 0.2s, color 0.2s;
            text-decoration: none !important;
            font-size: 1.12rem;
        }

        .location-item:hover {
            color: #2D5BFF !important;
            text-decoration: none !important;
        }

        ul li.location-item a {
            text-decoration: none !important;
        }

        ul {
            list-style: none;
        }

        a,
        a:hover,
        a:focus,
        a:active {
            text-decoration: none !important;
        }
    </style>

    <div class="min-h-screen flex">

        <!-- MAIN CONTENT -->
        <main id="mainContent" class="flex-1 ml-72 mr-[300px] px-6 pt-28 max-w-[700px] mx-auto">

            <div class="flex items-center mb-4">
                <a href="#" id="backBtn" class="flex items-center text-white no-underline">
                    <h3 class="bi bi-chevron-left text-7xl inline-block">Location</h3>
                </a>
            </div>


            <!-- Luzon -->
            <div class="mt-5">
                <h3 class="font-semibold mb-3 text-lg text-white">Luzon</h3>
                <ul class="list-none p-0">
                    <li class="location-item py-3 px-2 border-b border-[#2A2D35] cursor-pointer text-white"
                        data-province="Pampanga">
                        Pampanga
                    </li>
                    <li class="location-item py-3 px-2 border-b border-[#2A2D35] cursor-pointer text-white"
                        data-province="Cavite">
                        Cavite
                    </li>
                    <li class="location-item py-3 px-2 border-b border-[#2A2D35] cursor-pointer text-white"
                        data-province="Laguna">
                        Laguna
                    </li>
                </ul>
            </div>
        </main>

        @section('left-sidebar')
            <a href="../frontend/profile.php" id="profile" class="no-underline flex items-center gap-3 mt-4">
                <img id="petImage" src="./assets/img/hero.png" alt="profile"
                    class="rounded-full w-[50px] h-[50px] object-cover bg-[#333]">
                <div>
                    <h6 id="petName" class="text-white font-semibold mb-2 text-lg">Pet Name</h6>
                    <small id="petBreed" class="text-gray-400 text-base">Breed</small>
                </div>
            </a>

            <!-- breeding -->
            <a href="#" class="no-underline flex items-center gap-4 mt-5 p-2 rounded hover:bg-[#1F222A]">
                <i class="bi bi-heart text-white text-lg"></i>
                <h5 class="text-white mb-0 text-base mt-1">Breeding</h5>
            </a>

            <!-- playdate -->
            <a href="#" class="no-underline flex items-center gap-4 mt-3 p-2 rounded hover:bg-[#1F222A]">
                <i class="bi bi-calendar-event text-white text-lg"></i>
                <h5 class="text-white mb-0 text-base mt-1">Play Date</h5>
            </a>
        @endsection
    </div>

    @push('scripts')
        <script>
            // Back button
            document.getElementById('backBtn').addEventListener('click', function (e) {
                e.preventDefault();
                window.history.back();
            });

            // City data mapping - MUST match the exact names in home/index.blade.php
            const cityData = {
                'Pampanga': ['Angeles City', 'Mabalacat City', 'San Fernando City', 'Mexico', 'Bacolor', 'Guagua', 'Porac', 'Santa Rita', 'Magalang'],
                'Cavite': ['Bacoor City', 'Imus City', 'Dasmariñas City', 'Tagaytay City', 'General Trias', 'Trece Martires City', 'Kawit', 'Rosario', 'Silang', 'Tanza'],
                'Laguna': ['Calamba City', 'Santa Rosa City', 'Biñan City', 'San Pedro City', 'Cabuyao City', 'San Pablo City', 'Los Baños', 'Pagsanjan', 'Sta. Cruz', 'Bay']
            };

            // Function to normalize city names (strip " City" suffix for database matching)
            function normalizeCity(cityName) {
                return cityName.replace(/ City$/, '');
            }

            // Location items click handler
            const locationItems = document.querySelectorAll('.location-item');
            locationItems.forEach(item => {
                item.addEventListener('click', function () {
                    const province = this.getAttribute('data-province');
                    console.log('Province selected:', province);

                    // Get cities for this province
                    const cities = cityData[province] || [];

                    if (cities.length > 0) {
                        // Show city selection
                        showCityModal(province, cities);
                    } else {
                        // If no cities, just redirect to province
                        window.location.href = '/home?province=' + encodeURIComponent(province);
                    }
                });
            });

            function showCityModal(province, cities) {
                // Create modal HTML
                const modalHTML = `
                                    <div id="cityModalBackdrop" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
                                        <div style="background-color: #1B1E25; border-radius: 8px; padding: 24px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); border: 1px solid #3d4557;">
                                            <h4 style="color: white; margin-bottom: 20px; font-weight: 600;">Select City in ${province}</h4>
                                            <div style="display: flex; flex-direction: column; gap: 8px; max-height: 300px; overflow-y: auto;">
                                                ${cities.map(city => `
                                                    <button class="city-option" data-city="${city}" style="padding: 12px 16px; background-color: rgba(255,255,255,0.05); color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; cursor: pointer; text-align: left; transition: all 0.2s ease;">
                                                        ${city}
                                                    </button>
                                                `).join('')}
                                            </div>
                                            <button onclick="document.getElementById('cityModalBackdrop').remove();" style="margin-top: 16px; width: 100%; padding: 10px; background-color: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; cursor: pointer;">Cancel</button>
                                        </div>
                                    </div>
                                `;

                document.body.insertAdjacentHTML('beforeend', modalHTML);

                // Add hover effects
                document.querySelectorAll('.city-option').forEach(btn => {
                    btn.addEventListener('mouseover', function () {
                        this.style.backgroundColor = 'rgba(255,255,255,0.1)';
                    });
                    btn.addEventListener('mouseout', function () {
                        this.style.backgroundColor = 'rgba(255,255,255,0.05)';
                    });
                    btn.addEventListener('click', function () {
                        const city = this.getAttribute('data-city');
                        // Normalize city name by removing " City" suffix for database matching
                        const normalizedCity = normalizeCity(city);
                        window.location.href = `/home?province=${encodeURIComponent(province)}&city=${encodeURIComponent(normalizedCity)}`;
                    });
                });
            }
        </script>
    @endpush

@endsection