@extends('layouts.app')

@section('title', 'Search Weather - Global Weather')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <!-- Back to Home -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Global Weather
            </a>
        </div>

        <!-- Search Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-bounce-in">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6 text-white text-center">
                <div class="text-4xl mb-2">🔍</div>
                <h1 class="text-2xl font-bold">Search Weather</h1>
                <p class="text-blue-100">Find weather for any city worldwide</p>
            </div>

            <div class="p-8">
                <form action="/weather" method="POST" class="space-y-6" id="searchForm">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            City Name
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="city"
                                name="city" 
                                placeholder="e.g. Manila, Philippines" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg pl-12"
                                autofocus
                            >
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                🌍
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Try: London, New York, Tokyo, or any city name
                        </p>
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 text-lg shadow-sm hover:shadow-md transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submitBtn"
                    >
                        <span class="flex items-center justify-center space-x-2">
                            <span>Get Weather</span>
                            <span>🌤️</span>
                        </span>
                    </button>
                </form>

                <!-- Popular Cities Quick Search -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <p class="text-sm font-medium text-gray-700 mb-3">Popular cities:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Manila', 'New York', 'London', 'Tokyo', 'Paris'] as $popularCity)
                            <button 
                                type="button" 
                                onclick="document.getElementById('city').value='{{ $popularCity }}'; document.getElementById('searchForm').submit();"
                                class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full transition-colors"
                            >
                                {{ $popularCity }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="flex items-center justify-center space-x-2"><span>Searching...</span><span class="animate-spin">🔄</span></span>';
    });
</script>
@endpush