<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Shomobay') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-gray-900 antialiased">
        <div
            class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cover bg-center bg-no-repeat relative"
            style="
                background-image:
                    linear-gradient(rgba(5, 20, 14, 0.78), rgba(5, 20, 14, 0.88)),
                    url('{{ asset('images/shomobay-bg.jpg') }}'),
                    url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1800&q=80');
            "
        >
            <div class="absolute inset-0 bg-black/20"></div>

            <div class="relative z-10 w-full max-w-md px-6">
                <div class="flex flex-col items-center">
                    <a href="/" class="flex flex-col items-center">
                        <div class="rounded-3xl bg-white/90 p-4 shadow-xl">
                            <x-application-logo class="h-16 w-16" />
                        </div>

                        <h1 class="mt-4 text-3xl font-black text-white tracking-tight">
                            Shomobay
                        </h1>

                        <p class="mt-1 text-sm text-green-100 text-center">
                            Anti-Syndicate Neighborhood Bulk Buying
                        </p>
                    </a>
                </div>

                <div class="mt-8 bg-white/95 backdrop-blur-xl shadow-2xl overflow-hidden rounded-3xl border border-white/40">
                    <div class="px-8 py-8">
                        {{ $slot }}
                    </div>
                </div>

                <p class="mt-6 text-center text-sm text-green-100">
                    Fair grocery buying for smarter neighborhoods
                </p>
            </div>
        </div>
    </body>
</html>