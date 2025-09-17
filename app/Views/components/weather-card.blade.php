<div class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-slide-up"
    style="animation-delay: {{ $delay ?? 0 }}ms">
    <!-- City Header -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg">{{ $city['name'] }}</h3>
                <p class="text-blue-100 text-sm">{{ $city['country'] }}</p>
            </div>
            @if ($city['is_cached'] ?? false)
                <div class="bg-white/20 px-2 py-1 rounded-full text-xs flex items-center space-x-1">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span>Cached</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Weather Info -->
    <div class="p-6">
        <div class="text-center mb-4">
            <div class="text-6xl mb-2 animate-bounce-in">{{ $city['emoji'] }}</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">
                {{ round($city['temperature']) }}°C
            </div>
            <div class="text-gray-600 font-medium">{{ $city['description'] }}</div>
        </div>

        <!-- Additional Info -->
        <div class="bg-gray-50 rounded-lg p-3">
            <div class="grid grid-cols-1 gap-2 text-sm text-gray-600">
                <div class="flex items-center justify-center space-x-1">
                    <span>💨</span>
                    <span>Wind: {{ $city['windspeed'] }} km/h</span>
                </div>
                @if (isset($city['feels_like']))
                    <div class="flex items-center justify-center space-x-1">
                        <span>🌡️</span>
                        <span>Feels like: {{ round($city['feels_like']) }}°C</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
