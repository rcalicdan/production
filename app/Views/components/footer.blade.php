<footer class="bg-white border-t border-gray-100 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="text-center text-sm text-gray-600 space-y-2">
            <div class="flex items-center justify-center space-x-4">
                <span>Weather data by Open-Meteo API</span>
                <span>•</span>
                <span>Updated every hour</span>
                <span>•</span>
                <span>Built with PHP Fiber Async</span>
            </div>
            @if (isset($successful_cities) && isset($total_cities))
                <div class="flex items-center justify-center space-x-2">
                    <div class="flex space-x-1">
                        @for ($i = 0; $i < $successful_cities; $i++)
                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                        @endfor
                        @for ($i = $successful_cities; $i < $total_cities; $i++)
                            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                        @endfor
                    </div>
                    <span>{{ $successful_cities }}/{{ $total_cities }} cities loaded</span>
                </div>
            @endif
        </div>
    </div>
</footer>
