@extends('layouts.app')

@section('title', 'Global Weather Dashboard')

@section('content')
    @include('components.navigation')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Bar -->
        @include('components.stats-bar', [
            'successful_cities' => $successful_cities,
            'duration' => $duration,
            'total_cities' => $total_cities
        ])

        <!-- Weather Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($cities as $index => $city)
                @if($city)
                    @include('components.weather-card', [
                        'city' => $city,
                        'delay' => $index * 100
                    ])
                @endif
            @empty
                <div class="col-span-full">
                    @include('components.empty-state', [
                        'icon' => '🌧️',
                        'title' => 'No Weather Data Available',
                        'message' => 'Unable to fetch weather information at this time.',
                        'action' => 'Try Again',
                        'action_onclick' => 'location.reload()'
                    ])
                </div>
            @endforelse
        </div>
    </main>

    @include('components.footer', [
        'successful_cities' => $successful_cities,
        'total_cities' => $total_cities
    ])
@endsection

@push('scripts')
<script>
    let countdown = 60;
    const countdownElement = document.getElementById('countdown');
    
    const timer = setInterval(() => {
        countdown--;
        if (countdownElement) {
            countdownElement.textContent = countdown;
        }
        
        if (countdown <= 0) {
            location.reload();
        }
    }, 1000);

    document.querySelector('main')?.addEventListener('mouseenter', () => clearInterval(timer));

    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function() {
            if (this.href && !this.href.includes('#')) {
                this.style.opacity = '0.6';
                this.style.pointerEvents = 'none';
            }
        });
    });
</script>
@endpush