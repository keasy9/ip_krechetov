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
    <header class="fixed w-full flex justify-between p-5 items-center gap-3">
        <div class="text-red-400 text-3xl bold sm:text-4xl flex-grow md:flex-grow-0 lg:text-5xl">ИП Кречетов В. В.</div>
        <a class="hidden sm:flex items-center" href="#">
            <x-ionicon-location-sharp class="w-12 h-12 fill-red-400"/>
            <div class="hidden md:block">
                <span class="block">г. Барнаул</span>
                <span class="block">ул. Новосибирская, 32</span>
            </div>
        </a>
        <a href="tel:+79132141948" class="md:hidden">
            <x-ionicon-call class="w-10 h-10 fill-red-400 sm:w-12 sm:h-12"/>
        </a>
        <div class="hidden md:block">
            <a href="tel:+79132141948" class="block text-red-400 font-bold underline hover:decoration-solid transition-all decoration-dashed">+79132141948</a>
            <a href="tel:+79132141948" class="block text-red-400 font-bold underline hover:decoration-solid transition-all decoration-dashed">+79132141948</a>
        </div>
    </header>
    <main class="flex-grow">
        @yield('content')
    </main>
    <footer class="w-full p-5 flex gap-3 flex-col items-end sm:flex-row sm:items-center sm:justify-between">
        <div class="flex justify-between w-full items-center sm:contents">
            <div class="font-bold text-lg">ИП Кречетов В. В.</div>
            <div class="text-sm sm:flex-grow">2002 - 2004</div>
        </div>
        <a href="#" class="text-gray-400 underline hover:text-black transition-all" rel="nofollow">хочу такой же сайт</a>
    </footer>
    </body>
</html>
