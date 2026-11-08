
@extends('layouts.app')
@section('title', 'Weather - ACES')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-blue-950">
        Weather Updates
    </h1>
    <p class="text-gray-500 mt-2">
        Current weather conditions in Tagum City.
    </p>

    <div class="bg-white rounded-xl shadow-md p-8 mt-8 text-center">
        <div id="weatherIcon" class="text-6xl mb-4">⛅</div>

        <h2 class="text-2xl font-bold text-blue-950">
            Tagum City Weather
        </h2>

        <p id="temperature" class="text-5xl font-bold text-blue-950 mt-4">
            Loading...
        </p>

        <p id="weatherDescription" class="text-gray-500 mt-3">
            Getting weather information...
        </p>

        <div class="grid grid-cols-2 gap-4 mt-8">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-500">Humidity</p>
                <p id="humidity" class="font-bold text-blue-950">--</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-500">Wind Speed</p>
                <p id="wind" class="font-bold text-blue-950">--</p>
            </div>
        </div>

        <p id="weatherError" class="text-red-600 mt-5"></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('https://api.open-meteo.com/v1/forecast?latitude=7.4478&longitude=125.8078&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=Asia%2FManila')
        .then(response => {
            if (!response.ok) {
                throw new Error('Weather service unavailable.');
            }
            return response.json();
        })
        .then(data => {
            const weather = data.current;
            const codes = {
                0: ['☀️', 'Clear sky'],
                1: ['🌤️', 'Mainly clear'],
                2: ['⛅', 'Partly cloudy'],
                3: ['☁️', 'Overcast'],
                45: ['🌫️', 'Foggy'],
                48: ['🌫️', 'Depositing rime fog'],
                51: ['🌦️', 'Light drizzle'],
                53: ['🌦️', 'Moderate drizzle'],
                55: ['🌧️', 'Dense drizzle'],
                61: ['🌧️', 'Slight rain'],
                63: ['🌧️', 'Moderate rain'],
                65: ['🌧️', 'Heavy rain'],
                80: ['🌦️', 'Rain showers'],
                81: ['🌧️', 'Moderate rain showers'],
                82: ['⛈️', 'Violent rain showers'],
                95: ['⛈️', 'Thunderstorm']
            };

            const [icon, description] =
                codes[weather.weather_code] || ['🌡️', 'Weather update'];

            document.getElementById('weatherIcon').textContent = icon;
            document.getElementById('temperature').textContent =
                weather.temperature_2m + '°C';
            document.getElementById('weatherDescription').textContent =
                description;
            document.getElementById('humidity').textContent =
                weather.relative_humidity_2m + '%';
            document.getElementById('wind').textContent =
                weather.wind_speed_10m + ' km/h';
        })
        .catch(error => {
            document.getElementById('temperature').textContent = '--';
            document.getElementById('weatherDescription').textContent =
                'Unable to load weather information.';
            document.getElementById('weatherError').textContent =
                error.message;
        });
});
</script>
@endsection
