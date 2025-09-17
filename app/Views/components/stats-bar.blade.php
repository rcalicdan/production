<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 animate-fade-in">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $successful_cities }}</div>
                <div class="text-sm text-gray-600">Cities Updated</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ number_format($duration * 1000, 0) }}ms</div>
                <div class="text-sm text-gray-600">Load Time</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $total_cities }}</div>
                <div class="text-sm text-gray-600">Total Cities</div>
            </div>
        </div>
        <div class="text-sm text-gray-500 text-right">
            <div>Last updated: {{ date('H:i T') }}</div>
            <div class="flex items-center space-x-2 mt-1">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span>Auto-refresh in <span id="countdown">60</span>s</span>
            </div>
        </div>
    </div>
</div>
