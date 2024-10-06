<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app_name') }}</title>

        @vite(['resources/js/app.ts'])
        @vite(['resources/css/app.css'])
    </head>

    <body>
    <header class="fixed w-full flex justify-between p-5">
        <div class="text-red-400 text-5xl">ИП Кречетов В. В.</div>
        <div class="flex">
            <icon file="location" class="w-12 fill-red-400"></icon>
            <div>
                <span class="block">г. Барнаул</span>
                <span class="block">ул. Новосибирская, 32</span>
            </div>
        </div>
        <div>
            <a href="tel:+79132141948" class="block">+79132141948</a>
            <a href="tel:+79132141948" class="block">+79132141948</a>
        </div>
    </header>
    @yield('content')
    <footer></footer>
    </body>
</html>
