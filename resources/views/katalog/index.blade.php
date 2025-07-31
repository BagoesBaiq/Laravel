<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Makanan Nusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/richeditor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scroll.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">

    <script>
    let searchTimeout;
    let isFiltering = false;
    let currentRecipeIndex = null;

    function openRecipeModal(index) {
        try {
            currentRecipeIndex = index;
            const modal = document.getElementById('recipe-modal');
            const modalContent = document.getElementById('modal-recipe-content');
            const modalTitle = document.getElementById('modal-recipe-title');
            const modalImage = document.getElementById('modal-recipe-image');
            const modalRegion = document.getElementById('modal-recipe-region');
            const modalDescription = document.getElementById('modal-recipe-description');

            // Ambil card berdasarkan index
            const cards = document.querySelectorAll('.product-card');
            const card = cards[index];
            if (!card) { alert('Card tidak ditemukan!'); return; }

            const productName = card.querySelector('h2').textContent;
            const productImage = card.querySelector('img').src;
            const productRegion = card.querySelector('.text-medium-brown').textContent;
            const productDescription = card.querySelector('.rich-content').innerHTML;
            const resepDiv = document.getElementById(`resep-${index}`);
            let recipeContent = '';
            if (resepDiv && resepDiv.querySelector('.rich-content')) {
                recipeContent = resepDiv.querySelector('.rich-content').innerHTML;
            } else {
                recipeContent = 'Resep tidak ditemukan.';
            }

            modalTitle.textContent = productName;
            modalImage.src = productImage;
            modalImage.alt = productName;
            modalRegion.textContent = productRegion;
            modalDescription.innerHTML = productDescription;
            modalContent.innerHTML = recipeContent;

            modal.classList.remove('hidden');
            modal.classList.add('modal-enter');
            modal.style.zIndex = 9999;
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                modal.classList.remove('modal-enter');
            }, 300);
        } catch (e) {
            alert('Terjadi error saat membuka modal: ' + e.message);
        }
    }

    function closeRecipeModal() {
        const modal = document.getElementById('recipe-modal');
        modal.classList.add('modal-exit');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('modal-exit');
            document.body.style.overflow = 'auto'; // Enable scrolling
            currentRecipeIndex = null;
        }, 200);
    }

    function handleModalClick(event) {
        const modal = document.getElementById('recipe-modal');
        if (event.target === event.currentTarget) {
            closeRecipeModal();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && currentRecipeIndex !== null) {
            closeRecipeModal();
        }
    });

    function filterProducts(autoScroll = false) {
        if (isFiltering) return;
        isFiltering = true;

        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const categoryFilter = document.getElementById('category-filter').value.toLowerCase();
        const regionFilter = document.getElementById('region-filter').value.toLowerCase();

        const productCards = document.querySelectorAll('.product-card');
        const resultsGrid = document.querySelector('.results-grid');
        let visibleCount = 0;
        let delay = 0;

        document.getElementById('results-count').classList.add('shimmer-effect');

        productCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
        });

        setTimeout(() => {
            productCards.forEach((card, index) => {
                const productName = card.dataset.nama.toLowerCase();
                const productCategory = card.dataset.kategori.toLowerCase();
                const productRegion = card.dataset.daerah.toLowerCase();

                const matchesSearch = productName.includes(searchTerm);
                const matchesCategory = categoryFilter === '' || productCategory === categoryFilter;
                const matchesRegion = regionFilter === '' || productRegion === regionFilter;

                if (matchesSearch && matchesCategory && matchesRegion) {
                    card.style.display = 'block';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                        card.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    }, delay * 100);

                    delay++;
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            setTimeout(() => {
                document.getElementById('results-count').classList.remove('shimmer-effect');
                document.getElementById('results-count').textContent =
                    `Menampilkan ${visibleCount} produk`;

                const noResultsDiv = document.getElementById('no-results');
                const productGrid = resultsGrid;

                if (visibleCount === 0) {
                    noResultsDiv.classList.remove('hidden');
                    noResultsDiv.style.animation = 'fadeInScale 0.5s ease-out';
                    productGrid.classList.add('opacity-50');
                } else {
                    noResultsDiv.classList.add('hidden');
                    productGrid.classList.remove('opacity-50');

                    if (autoScroll) {
                        setTimeout(() => {
                            productGrid.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }, 300);
                    }
                }
                isFiltering = false;
            }, 500);
        }, 150);
    }

    function handleSearchInput() {
        clearTimeout(searchTimeout);

        const searchInput = document.getElementById('search-input');
        searchInput.classList.add('ring-2', 'ring-yellow-300');

        searchTimeout = setTimeout(() => {
            searchInput.classList.remove('ring-2', 'ring-yellow-300');
            filterProducts(false);
        }, 500);
    }

    function handleSearchEnter(event) {
        if (event.key === 'Enter') {
            clearTimeout(searchTimeout);
            const searchInput = document.getElementById('search-input');
            searchInput.classList.remove('ring-2', 'ring-yellow-300');
            filterProducts(true);
        }
    }

    function handleFilterChange() {
        filterProducts(false);
    }

    function clearFilters() {
        document.getElementById('search-input').value = '';
        document.getElementById('category-filter').value = '';
        document.getElementById('region-filter').value = '';

        const filterSection = document.querySelector('.filter-section');
        filterSection.classList.add('filter-pulse');
        setTimeout(() => filterSection.classList.remove('filter-pulse'), 300);

        filterProducts(false);
    }

    function initScrollAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = `${index * 0.1}s`;
                    entry.target.classList.add('card-animate');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        document.querySelectorAll('.product-card').forEach(card => {
            observer.observe(card);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initScrollAnimations();
        filterProducts(false);
        document.querySelector('h1').classList.add('floating');
    });

    function toggleResep(index) {
        const resepDiv = document.getElementById('resep-' + index);
        const btn = document.getElementById('btn-resep-' + index);
        const card = btn.closest('.product-card');

        if (resepDiv.classList.contains('hidden')) {
            resepDiv.classList.remove('hidden');
            resepDiv.classList.add('recipe-slide');
            btn.innerText = 'Tutup Resep';

            card.classList.add('filter-pulse');
            setTimeout(() => card.classList.remove('filter-pulse'), 300);

            setTimeout(() => {
                const rect = resepDiv.getBoundingClientRect();
                const windowHeight = window.innerHeight;

                if (rect.bottom > windowHeight - 50) {
                    resepDiv.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }
            }, 300);
        } else {
            resepDiv.classList.add('hidden');
            resepDiv.classList.remove('recipe-slide');
            btn.innerText = 'Lihat Resep';
        }
    }

    </script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gray-800 mb-4 floating" style="font-family: 'Playfair Display', serif;">
                Makanan Nusantara
            </h1>
            <p class="text-gray-600 text-lg" style="font-family: 'Merriweather', serif;">Jelajahi kekayaan kuliner tradisional Indonesia</p>
        </div>

        <div class="filter-section bg-white rounded-2xl p-8 shadow-xl mb-8 backdrop-blur-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 bg-green-700 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">Filter Produk</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label for="search-input" class="block text-sm font-semibold text-gray-700">
                        🔍 Cari Produk
                    </label>
                    <input
                        type="text"
                        id="search-input"
                        placeholder="Ketik nama makanan..."
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-yellow-300 focus:border-yellow-500 transition-all duration-300 bg-white"
                        oninput="handleSearchInput()"
                        onkeydown="handleSearchEnter(event)">
                </div>

                <div class="space-y-2">
                    <label for="category-filter" class="block text-sm font-semibold text-gray-700">
                        🍽️ Kategori
                    </label>
                    <select
                        id="category-filter"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-yellow-300 focus:border-yellow-500 transition-all duration-300 bg-white"
                        onchange="handleFilterChange()"
                        oninput="handleSearchInput()"
                        onkeydown="handleSearchEnter(event)">
                        <option value="">Semua Kategori</option>
                        <option value="makanan">🍛 Makanan</option>
                        <option value="minuman">🥤 Minuman</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="region-filter" class="block text-sm font-semibold text-gray-700">
                        🗺️ Daerah
                    </label>
                    <select
                        id="region-filter"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-yellow-300 focus:border-yellow-500 transition-all duration-300 bg-white"
                        onchange="handleFilterChange()"
                        oninput="handleSearchInput()"
                        onkeydown="handleSearchEnter(event)">
                        <option value="">Semua Daerah</option>
                        @foreach($produks->unique('daerah') as $item)
                            <option value="{{ strtolower($item->daerah) }}">{{ $item->daerah->nama_daerah }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button
                        onclick="clearFilters()"
                        class="w-full px-6 py-3 bg-gray-700 text-white rounded-xl hover:bg-gray-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                        ✨ Reset Filter
                    </button>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-100 rounded-xl border-l-4 border-yellow-500">
                <span id="results-count" class="text-gray-700 font-medium">Menampilkan {{ count($produks) }} produk</span>
            </div>
        </div>

        <div class="results-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($produks as $index => $item)
                <div class="product-card card-hover card-gradient rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col h-[500px]"
                    data-nama="{{ $item->nama }}"
                    data-kategori="{{ $item->kategori ?? 'makanan' }}"
                    data-daerah="{{ $item->daerah }}"
                     style="--delay: {{ $index * 0.1 }}s">

                    <div class="relative overflow-hidden rounded-xl mb-4 group">
                        <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://via.placeholder.com/300' }}"
                            class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-110"
                            alt="{{ $item->nama }}">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    <div class="flex flex-col flex-grow">
                        <div class="space-y-3 flex-grow">
                            <h2 class="text-xl font-bold text-gray-800 group-hover:text-yellow-600 transition-colors duration-300 line-clamp-1">
                                {{ $item->nama }}
                            </h2>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-500">📍</span>
                                    <p class="text-sm font-medium text-gray-600">{{ $item->daerah->nama_daerah ?? 'Tidak Diketahui' }}</p>
                                </div>
                                @if(isset($item->kategori))
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border-2 transform hover:scale-110 transition-transform duration-200
                                        {{ $item->kategori === 'makanan' ? 'bg-green-100 text-green-700 border-green-200' :
                                        'bg-yellow-100 text-yellow-700 border-yellow-200' }}">
                                        {{ $item->kategori === 'makanan' ? '🍛' : '🥤' }} {{ ucfirst($item->kategori) }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm text-gray-600 rich-content p-3 bg-gray-50 rounded-xl h-24 overflow-y-auto">
                                {!! $item->deskripsi !!}
                            </div>
                        </div>

                        <div class="mt-4">
                            <button
                                onclick="openRecipeModal({{ $index }})"
                                id="btn-resep-{{ $index }}"
                                class="w-full px-4 py-3 bg-yellow-500 text-gray-800 rounded-xl hover:bg-yellow-600 transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-lg font-semibold">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span>Lihat Resep</span>
                                </div>
                            </button>
                        </div>

                        <div id="resep-{{ $index }}" class="hidden mt-4 p-4 bg-red-100 rounded-xl border-l-4 border-red-400">
                            <div class="rich-content text-sm text-gray-700">
                                {!! $item->resep !!}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-6xl mb-4">🍽️</div>
                    <p class="text-gray-500 text-lg">Tidak ada produk yang ditemukan</p>
                </div>
            @endforelse
        </div>

        <div id="no-results" class="hidden text-center py-12">
            <div class="text-6xl mb-4">🔍</div>
            <p class="text-gray-500 text-xl mb-4">Tidak ada produk yang sesuai dengan filter yang dipilih</p>
            <button onclick="clearFilters()" class="px-6 py-3 bg-yellow-500 text-gray-800 rounded-xl hover:bg-yellow-600 transform hover:scale-105 transition-all duration-300 shadow-lg font-semibold">
                ✨ Reset semua filter
            </button>
        </div>

        <div id="recipe-modal" class="fixed inset-0 z-[9999] overflow-y-auto bg-black bg-opacity-50 hidden" onclick="handleModalClick(event)">
            <div class="min-h-screen px-4 text-center">
                <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>

                <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                    <button onclick="closeRecipeModal()" class="absolute top-4 right-4 p-2 rounded-full bg-gray-100 text-gray-500 hover:bg-red-100 hover:text-red-500 transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <div class="mt-2">
                        <h2 id="modal-recipe-title" class="text-2xl font-bold text-gray-900 mb-4"></h2>
                        <div id="modal-recipe-region" class="text-sm text-gray-500 mb-3"></div>

                        <div class="relative w-full pb-[56.25%] mb-4 rounded-xl overflow-hidden">
                            <img id="modal-recipe-image" class="absolute inset-0 w-full h-full object-cover" src="" alt="">
                        </div>

                        <div class="space-y-4">
                            <div id="modal-recipe-description" class="prose max-w-none text-gray-700"></div>
                            <div id="modal-recipe-content" class="prose max-w-none text-gray-700"></div>
                        </div>

                        <div class="mt-6 flex justify-center">
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
