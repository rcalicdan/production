@extends('layouts.app')

@section('title', isset($error) ? 'Error - Weather Search' : $city . ' Weather')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-lg w-full">
        <!-- Navigation -->
        <div class="text-center mb-6 space-x-4">
            <a href="/" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Home
            </a>
            <a href="/weather/search" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                🔍 Search Again
            </a>
        </div>

        <!-- Results Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-bounce-in">
            @if (isset($error))
                <!-- Error State -->
                <div class="bg-gradient-to-r from-red-500 to-pink-500 p-6 text-white text-center">
                    <div class="text-4xl mb-2">❌</div>
                    <h1 class="text-2xl font-bold">Oops!</h1>
                    <p class="text-red-100">Something went wrong</p>
                </div>
                <div class="p-8 text-center">
                    <p class="text-gray-600 text-lg mb-6">{{ $error }}</p>
                    <div class="space-y-3">
                        <a href="/weather/search" 
                           class="block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200">
                            Try Another City
                        </a>
                        <a href="/" 
                           class="block text-blue-600 hover:text-blue-700 font-medium">
                            Back to Home
                        </a>
                    </div>
                </div>
            @else
                <!-- Success State -->
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6 text-white text-center">
                    <h1 class="text-2xl font-bold">{{ $city }}</h1>
                    <p class="text-blue-100">Current Weather Conditions</p>
                </div>

                <div class="p-8">
                    <div class="text-center mb-6">
                        <div class="text-8xl mb-4 animate-bounce-in">{{ $weather['emoji'] }}</div>
                        <div class="text-4xl font-bold text-gray-900 mb-2">{{ round($temperature) }}°C</div>
                        <div class="text-xl text-gray-600 font-medium mb-4">{{ $weather['description'] }}</div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 mb-6">
                        <div class="grid grid-cols-1 gap-4 text-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl">💨</span>
                                    <span class="font-medium">Wind Speed</span>
                                </div>
                                <span class="text-lg font-bold">{{ $windspeed }} km/h</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl">🌡️</span>
                                    <span class="font-medium">Temperature</span>
                                </div>
                                <span class="text-lg font-bold">{{ round($temperature) }}°C</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3">
                        <a href="/weather/search" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-all duration-200 text-center">
                            🔍 New Search
                        </a>
                        <a href="/" 
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-lg font-medium transition-all duration-200 text-center">
                            🌍 View All Cities
                        </a>
                    </div>
                </div>
            @endif

            <!-- Footer Info -->
            <div class="bg-gray-50 px-8 py-4">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center space-x-4">
                        @if (isset($is_cached) && $is_cached)
                            <span class="text-green-600 font-medium flex items-center space-x-1">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                <span>Cached Data</span>
                            </span>
                        @else
                            <span class="text-blue-600 font-medium flex items-center space-x-1">
                                <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                                <span>Live Data</span>
                            </span>
                        @endif

                        @if (isset($location_cached) && $location_cached)
                            <span class="text-green-600">📍 Location Cached</span>
                        @endif
                    </div>

                    @if (isset($duration))
                        <span class="text-gray-500">
                            {{ number_format($duration * 1000, 2) }}ms
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection