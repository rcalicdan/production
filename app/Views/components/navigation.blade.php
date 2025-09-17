<header class="bg-white/80 backdrop-blur-sm shadow-sm border-b border-gray-100 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
            <a href="/" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                <div class="text-3xl">🌍</div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Global Weather</h1>
                    <p class="text-sm text-gray-600">Real-time weather from major cities worldwide</p>
                </div>
            </a>
            
            <div class="flex items-center space-x-4">
                @if($currentPath !== '/')
                    <a href="/" 
                       class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                        🌍 Home
                    </a>
                @endif
                
                @if($currentPath !== '/weather/search')
                    <a href="/weather/search" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        🔍 Search City
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>