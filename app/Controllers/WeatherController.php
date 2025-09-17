<?php

namespace App\Controllers;

use Rcalicdan\FiberAsync\Api\Http;
use Rcalicdan\FiberAsync\Http\Response as HttpResponse;
use Rcalicdan\FiberAsync\Promise\Interfaces\PromiseInterface;
use Rcalicdan\FiberAsync\Promise\Promise;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use function App\view;

class WeatherController
{
    /**
     * Major cities with their coordinates for the home page
     */
    private const MAJOR_CITIES = [
        ['name' => 'New York', 'country' => 'USA', 'lat' => 40.7128, 'lon' => -74.0060],
        ['name' => 'London', 'country' => 'UK', 'lat' => 51.5074, 'lon' => -0.1278],
        ['name' => 'Tokyo', 'country' => 'Japan', 'lat' => 35.6762, 'lon' => 139.6503],
        ['name' => 'Paris', 'country' => 'France', 'lat' => 48.8566, 'lon' => 2.3522],
        ['name' => 'Sydney', 'country' => 'Australia', 'lat' => -33.8688, 'lon' => 151.2093],
        ['name' => 'Dubai', 'country' => 'UAE', 'lat' => 25.2048, 'lon' => 55.2708],
        ['name' => 'Singapore', 'country' => 'Singapore', 'lat' => 1.3521, 'lon' => 103.8198],
        ['name' => 'Hong Kong', 'country' => 'China', 'lat' => 22.3193, 'lon' => 114.1694],
    ];

    /**
     * Display the home page with major cities weather
     */
    public function home(Request $request, Response $response)
    {
        return run(function () use ($request, $response) {
            $startTime = microtime(true);

            $cityPromises = [];
            foreach (self::MAJOR_CITIES as $city) {
                $cityPromises[] = $this->fetchCityWeather($city);
            }

            $citiesWeather = await(Promise::all($cityPromises));

            $duration = microtime(true) - $startTime;

            $viewData = [
                'cities' => array_filter($citiesWeather),
                'duration' => $duration,
                'total_cities' => count(self::MAJOR_CITIES),
                'successful_cities' => count(array_filter($citiesWeather)),
                'currentPath' => $request->getUri()->getPath(),
            ];

            return view($response, 'weather-home', $viewData);
        });
    }

    /**
     * Display the search form
     */
    public function showForm(Request $request, Response $response)
    {
        return view($response, 'weather-form', [
            'currentPath' => $request->getUri()->getPath()
        ]);
    }

    /**
     * Handle the form submission, fetch weather, and display results
     */
    public function getWeather(Request $request, Response $response)
    {
        $data = (array)$request->getParsedBody();
        $city = trim($data['city'] ?? '');

        if (empty($city)) {
            return view($response, 'weather-results', [
                'error' => 'Please enter a city name.',
                'currentPath' => $request->getUri()->getPath()
            ]);
        }

        return run(function () use ($request, $response, $city) {
            $startTime = microtime(true);

            $location = await($this->fetchLocationData($city));
            if ($location === null) {
                return view($response, 'weather-results', [
                    'error' => "Could not find the city: '{$city}'. Please try another.",
                    'duration' => microtime(true) - $startTime,
                    'currentPath' => $request->getUri()->getPath(),
                ]);
            }

            $weatherResponse = await($this->fetchCurrentWeather($location['latitude'], $location['longitude']));
            if ($weatherResponse === null) {
                return view($response, 'weather-results', [
                    'error' => 'Could not retrieve weather data at this time.',
                    'duration' => microtime(true) - $startTime,
                    'currentPath' => $request->getUri()->getPath(),
                ]);
            }

            $duration = microtime(true) - $startTime;
            $weather = $weatherResponse->json()['current_weather'];

            $viewData = [
                'city' => $location['displayName'],
                'temperature' => $weather['temperature'],
                'windspeed' => $weather['windspeed'],
                'weather' => $this->interpretWeatherCode($weather['weathercode']),
                'is_cached' => $weatherResponse->header('X-Cache-Hit') === 'true',
                'location_cached' => $location['is_cached'] ?? false,
                'duration' => $duration,
                'currentPath' => $request->getUri()->getPath(),
            ];

            return view($response, 'weather-results', $viewData);
        });
    }

    /**
     * Fetch weather for a specific city with coordinates
     */
    private function fetchCityWeather(array $cityData): PromiseInterface
    {
        return async(function () use ($cityData) {
            try {
                $weatherResponse = await($this->fetchCurrentWeather($cityData['lat'], $cityData['lon']));

                if ($weatherResponse === null) {
                    error_log("Failed to fetch weather for {$cityData['name']}");
                    return null;
                }

                $weather = $weatherResponse->json()['current_weather'];
                $weatherInfo = $this->interpretWeatherCode($weather['weathercode']);

                return [
                    'name' => $cityData['name'],
                    'country' => $cityData['country'],
                    'temperature' => $weather['temperature'],
                    'windspeed' => $weather['windspeed'],
                    'description' => $weatherInfo['description'],
                    'emoji' => $weatherInfo['emoji'],
                    'is_cached' => $weatherResponse->header('X-Cache-Hit') === 'true',
                ];
            } catch (\Throwable $e) {
                error_log("Error fetching weather for {$cityData['name']}: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Asynchronously fetches geocoding data for a given city with caching
     */
    private function fetchLocationData(string $city): PromiseInterface
    {
        return async(function () use ($city) {
            $encodedCity = urlencode($city);
            $geocodeUrl = "https://geocoding-api.open-meteo.com/v1/search?name={$encodedCity}&count=1";

            $response = await(http()->cache(86400)->get($geocodeUrl));

            $isCached = $response->header('X-Cache-Hit') === 'true';

            error_log("Geocoding API - Cache hit: " . ($isCached ? 'Yes' : 'No') . " for city: {$city}");

            if (!$response->ok() || empty($response->json()['results'])) {
                error_log("Geocoding API failed or returned empty results for city: {$city}");
                return null;
            }

            $location = $response->json()['results'][0];

            return [
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'displayName' => $location['name'] . ", " . $location['country'],
                'is_cached' => $isCached,
            ];
        });
    }

    /**
     * Asynchronously fetches weather data for given coordinates, with caching
     */
    private function fetchCurrentWeather(float $latitude, float $longitude): PromiseInterface
    {
        return async(function () use ($latitude, $longitude) {
            $weatherUrl = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&current_weather=true";

            $response = await(http()->cache(3600)->get($weatherUrl));

            $isCached = $response->header('X-Cache-Hit') === 'true';

            error_log("Weather API - Cache hit: " . ($isCached ? 'Yes' : 'No') . " for coordinates: {$latitude},{$longitude}");

            if (!$response->ok()) {
                error_log("Weather API failed for coordinates: {$latitude},{$longitude}");
                return null;
            }

            return $response;
        });
    }

    /**
     * Converts a WMO weather code to a description and emoji
     */
    private function interpretWeatherCode(int $code): array
    {
        return match ($code) {
            0 => ['description' => 'Clear sky', 'emoji' => '☀️'],
            1, 2, 3 => ['description' => 'Mainly clear, partly cloudy', 'emoji' => '🌤️'],
            45, 48 => ['description' => 'Fog and depositing rime fog', 'emoji' => '🌫️'],
            51, 53, 55 => ['description' => 'Drizzle', 'emoji' => '💧'],
            61, 63, 65 => ['description' => 'Rain', 'emoji' => '🌧️'],
            66, 67 => ['description' => 'Freezing Rain', 'emoji' => '🥶'],
            71, 73, 75 => ['description' => 'Snow fall', 'emoji' => '🌨️'],
            80, 81, 82 => ['description' => 'Rain showers', 'emoji' => '🌦️'],
            85, 86 => ['description' => 'Snow showers', 'emoji' => '🌨️'],
            95, 96, 99 => ['description' => 'Thunderstorm', 'emoji' => '⛈️'],
            default => ['description' => 'Unknown', 'emoji' => '🤷'],
        };
    }
}
