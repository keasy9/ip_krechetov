<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app_name') }}</title>

        @vite(['resources/js/app.ts'])
        @vite(['resources/css/app.css'])
    </head>
    <body class="flex flex-col min-h-screen">
    <header class="fixed w-full flex justify-between p-5 items-center gap-3 z-20 bg-black bg-opacity-50 transition">
        <div class="text-red-500 text-3xl bold sm:text-4xl flex-grow md:flex-grow-0 lg:text-5xl">{{ $baseData['header']['site-name'] ?? $baseData['site-name'] ?? '' }}</div>
        @if(!empty($baseData['header']['address']))
            <a class="hidden sm:flex items-center" href="#">
                <x-ionicon-location-sharp class="w-12 h-12 fill-red-500"/>
                <div class="hidden md:block text-white font-bold">
                    @foreach(explode("\n", $baseData['header']['address']) as $string)
                        <span class="block">{{ $string }}</span>
                    @endforeach
                </div>
            </a>
        @endif
        @if(!empty($baseData['header']['phone']))
            <a href="tel:{{ reset($baseData['header']['phone']) }}" class="md:hidden">
                <x-ionicon-call class="w-10 h-10 fill-red-500 sm:w-12 sm:h-12"/>
            </a>
            <div class="hidden md:block">
                @foreach($baseData['header']['phone'] as $phone)
                    <a href="tel:{{ $phone }}" class="block text-red-500 font-bold underline hover:decoration-solid transition-all decoration-dashed">{{ $phone }}</a>
                @endforeach
            </div>
        @endif
    </header>
    <main class="flex-grow">
        @yield('content')
    </main>
    <footer class="w-full p-5 flex gap-3 flex-col items-end sm:flex-row sm:items-center sm:justify-between bg-black text-white">
        <div class="flex justify-between w-full items-center sm:contents">
            <div class="font-bold text-lg">{{ $baseData['footer']['site-name'] ?? $baseData['site-name'] ?? '' }}</div>
            <div class="text-sm sm:flex-grow">{{ !empty($baseData['footer']['start-year']) ? "{$baseData['footer']['start-year']} - " : '' }}{{ now()->format('Y') }}</div>
        </div>
        <a href="#" class="text-gray-400 underline hover:text-black transition-all" rel="nofollow">хочу такой же сайт</a>
    </footer>
    </body>
</html>
