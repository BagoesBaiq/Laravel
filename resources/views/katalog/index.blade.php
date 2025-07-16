<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Makanan Nusantara</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
        <link rel="stylesheet" href="{{ asset('css/richeditor.css') }}">
            <link rel="stylesheet" href="{{ asset('css/scroll.css') }}">
    <script>
    let searchTimeout;
    let isFiltering = false;

    function toggleResep(index) {
        const resepDiv = document.getElementById('resep-' + index);
        const btn = document.getElementById('btn-resep-' + index);
        const card = btn.closest('.product-card');
        
        if (resepDiv.classList.contains('hidden')) {
            resepDiv.classList.remove('hidden');
            resepDiv.classList.add('recipe-slide');
            btn.innerText = 'Tutup Resep';
            
            // Add pulse effect to card
            card.classList.add('filter-pulse');
            setTimeout(() => card.classList.remove('filter-pulse'), 300);
            
            // Scroll to show the recipe only if it's not visible
            setTimeout(() => {
                const rect = resepDiv.getBoundingClientRect();
                const windowHeight = window.innerHeight;
                
                // Only scroll if recipe is not visible and user clicked the button
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
        
        // Add loading shimmer effect
        document.getElementById('results-count').classList.add('shimmer-effect');
        
        // Hide all cards first with fade out
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
                    
                    // Animate in with staggered delay
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
            
            // Update results counter with animation
            setTimeout(() => {
                document.getElementById('results-count').classList.remove('shimmer-effect');
                document.getElementById('results-count').textContent = 
                    `Menampilkan ${visibleCount} produk`;
                    
                // Handle no results
                const noResultsDiv = document.getElementById('no-results');
                const productGrid = resultsGrid;
                
                if (visibleCount === 0) {
                    noResultsDiv.classList.remove('hidden');
                    noResultsDiv.style.animation = 'fadeInScale 0.5s ease-out';
                    productGrid.classList.add('opacity-50');
                } else {
                    noResultsDiv.classList.add('hidden');
                    productGrid.classList.remove('opacity-50');
                    
                    // Auto scroll hanya jika diminta (Enter key)
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
        
        // Add typing indicator
        const searchInput = document.getElementById('search-input');
        searchInput.classList.add('ring-2', 'ring-blue-300');
        
        searchTimeout = setTimeout(() => {
            searchInput.classList.remove('ring-2', 'ring-blue-300');
            filterProducts(false); // Don't auto-scroll during typing
        }, 500);
    }

    function handleSearchEnter(event) {
        if (event.key === 'Enter') {
            clearTimeout(searchTimeout);
            const searchInput = document.getElementById('search-input');
            searchInput.classList.remove('ring-2', 'ring-blue-300');
            filterProducts(true); // Auto-scroll hanya saat Enter
        }
    }

    function handleFilterChange() {
        filterProducts(false); // Don't auto-scroll when just changing filters
    }

    function clearFilters() {
        document.getElementById('search-input').value = '';
        document.getElementById('category-filter').value = '';
        document.getElementById('region-filter').value = '';
        
        // Add clear animation
        const filterSection = document.querySelector('.filter-section');
        filterSection.classList.add('filter-pulse');
        setTimeout(() => filterSection.classList.remove('filter-pulse'), 300);
        
        filterProducts(false); // Don't auto-scroll when clearing
    }

    // Scroll animations observer
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

    // Initialize everything when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initScrollAnimations();
        filterProducts(false); // Initial load - no scroll
        
        // Add floating animation to title
        document.querySelector('h1').classList.add('floating');
    });
    </script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

    <div class="container mx-auto px-4 py-8">
        <!-- Hero Title -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-4">
                Makanan Nusantara
            </h1>
            <p class="text-gray-600 text-lg">Jelajahi kekayaan kuliner tradisional Indonesia</p>
        </div>

        <!-- Filter Section -->
        <div class="filter-section card-gradient rounded-2xl p-8 shadow-xl mb-8 backdrop-blur-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Filter Produk</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Search Input -->
                <div class="space-y-2">
                    <label for="search-input" class="block text-sm font-semibold text-gray-700">
                        🔍 Cari Produk
                    </label>
                    <input
                        type="text"
                        id="search-input"
                        placeholder="Ketik nama makanan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-300 focus:border-purple-500 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                        oninput="handleSearchInput()"
                        onkeydown="handleSearchEnter(event)">
                </div>

                <!-- Category Filter -->
                <div class="space-y-2">
                    <label for="category-filter" class="block text-sm font-semibold text-gray-700">
                        🍽️ Kategori
                    </label>
                    <select
                        id="category-filter"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-300 focus:border-purple-500 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                        onchange="handleFilterChange()"
                        oninput="handleSearchInput()"
                        onkeydown="handleSearchEnter(event)">
                        <option value="">Semua Kategori</option>
                        <option value="makanan">🍛 Makanan</option>
                        <option value="minuman">🥤 Minuman</option>
                    </select>
                </div>

                <!-- Region Filter -->
                <div class="space-y-2">
                    <label for="region-filter" class="block text-sm font-semibold text-gray-700">
                        🗺️ Daerah
                    </label>
                    <select
                        id="region-filter"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-300 focus:border-purple-500 transition-all duration-300 bg-white/80 backdrop-blur-sm"
                        onchange="handleFilterChange()"
                        oninput="handleSearchInput()"
                        onkeydown="handleSearchEnter(event)">
                        <option value="">Semua Daerah</option>
                        @foreach($produks->unique('daerah') as $item)
                            <option value="{{ strtolower($item->daerah) }}">{{ $item->daerah->nama_daerah }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Clear Filters Button -->
                <div class="flex items-end">
                    <button
                        onclick="clearFilters()"
                        class="w-full px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                        ✨ Reset Filter
                    </button>
                </div>
            </div>

            <!-- Results Counter -->
            <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl">
                <span id="results-count" class="text-gray-700 font-medium">Menampilkan {{ count($produks) }} produk</span>
            </div>
        </div>

        <!-- Katalog Produk -->
        <div class="results-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($produks as $index => $item)
                <div class="product-card card-hover card-gradient rounded-2xl p-6 shadow-lg hover:shadow-2xl"
                    data-nama="{{ $item->nama }}"
                    data-kategori="{{ $item->kategori ?? 'makanan' }}"
                    data-daerah="{{ $item->daerah }}"
                     style="--delay: {{ $index * 0.1 }}s">
                    
                    <div class="relative overflow-hidden rounded-xl mb-4 group">
                        <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://via.placeholder.com/300' }}"
                            class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-110" 
                            alt="{{ $item->nama }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    <div class="space-y-3">
                        <h2 class="text-2xl font-bold text-gray-800 group-hover:text-purple-600 transition-colors duration-300">
                            {{ $item->nama }}
                        </h2>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500">📍</span>
                                <p class="text-sm font-medium text-gray-600">{{ $item->daerah->nama_daerah ?? 'Tidak Diketahui' }}</p>
                            </div>
                            @if(isset($item->kategori))
                                <span class="px-3 py-1 text-xs font-bold rounded-full border-2 transform hover:scale-110 transition-transform duration-200
                                    {{ $item->kategori === 'makanan' ? 'bg-green-50 text-green-700 border-green-200' : 
                                    'bg-blue-50 text-blue-700 border-blue-200' }}">
                                    {{ $item->kategori === 'makanan' ? '🍛' : '🥤' }} {{ ucfirst($item->kategori) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Description -->
                        <div class="text-sm text-gray-600 rich-content p-3 bg-gray-50 rounded-xl">
                            {!! $item->deskripsi !!}
                        </div>
                        
                        <!-- Recipe Button -->
                        <button
                            onclick="toggleResep({{ $index }})"
                            id="btn-resep-{{ $index }}"
                            class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-xl hover:from-purple-600 hover:to-blue-600 transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-lg font-semibold">
                            Lihat Resep
                        </button>
                        
                        <!-- Recipe Content -->
                        <div id="resep-{{ $index }}" class="hidden mt-4 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border-l-4 border-orange-400">
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

        <!-- No Results Message -->
        <div id="no-results" class="hidden text-center py-12">
            <div class="text-6xl mb-4">🔍</div>
            <p class="text-gray-500 text-xl mb-4">Tidak ada produk yang sesuai dengan filter yang dipilih</p>
            <button onclick="clearFilters()" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-xl hover:from-purple-600 hover:to-blue-600 transform hover:scale-105 transition-all duration-300 shadow-lg font-semibold">
                ✨ Reset semua filter
            </button>
        </div>
    </div>

</body>
</html>