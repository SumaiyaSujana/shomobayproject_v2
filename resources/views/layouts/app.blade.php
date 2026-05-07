<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Shomobay') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background-color: #eaf8ed;
            }

            .shomobay-app-shell {
                min-height: 100vh;
                position: relative;
                overflow: hidden;
                background:
                    radial-gradient(circle at 12% 15%, rgba(34, 197, 94, 0.26), transparent 26%),
                    radial-gradient(circle at 88% 10%, rgba(132, 204, 22, 0.20), transparent 24%),
                    radial-gradient(circle at 20% 90%, rgba(22, 163, 74, 0.22), transparent 30%),
                    radial-gradient(circle at 88% 82%, rgba(187, 247, 208, 0.38), transparent 28%),
                    linear-gradient(135deg, #e8f8ec 0%, #f2fbf3 38%, #e4f6e9 70%, #fffdf2 100%);
            }

            /*
                Local post-login background image.
                The opacity is controlled here, so the image stays transparent and soft.
            */
            .shomobay-app-shell::before {
                content: '';
                position: absolute;
                inset: 0;
                pointer-events: none;
                opacity: 0.18;
                background-image: url('{{ asset('images/post-login-bg.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                filter: saturate(1.08) contrast(0.95);
            }

            /*
                Soft green overlay keeps text and cards readable.
            */
            .shomobay-app-shell::after {
                content: '';
                position: absolute;
                inset: 0;
                pointer-events: none;
                background:
                    linear-gradient(
                        135deg,
                        rgba(232, 248, 236, 0.72),
                        rgba(255, 253, 242, 0.60)
                    );
            }

            .shomobay-main-surface {
                position: relative;
                z-index: 1;
                min-height: calc(100vh - 80px);
                background: rgba(255, 255, 255, 0.04);
                backdrop-filter: blur(1px);
            }

            .shomobay-soft-header {
                background: rgba(255, 255, 255, 0.84);
                backdrop-filter: blur(14px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.72);
                box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            }

            .shomobay-app-shell .bg-white.shadow-sm.sm\:rounded-lg,
            .shomobay-app-shell .bg-white.overflow-hidden.shadow-sm.sm\:rounded-lg,
            .shomobay-app-shell .bg-white.p-6.shadow-sm.sm\:rounded-lg,
            .shomobay-app-shell .bg-white.p-6.shadow-sm.rounded-lg,
            .shomobay-app-shell .bg-white.shadow-sm.rounded-lg {
                background-color: rgba(255, 255, 255, 0.91) !important;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.82);
                box-shadow: 0 14px 34px rgba(20, 83, 45, 0.10);
                transition: transform 0.22s ease, box-shadow 0.22s ease;
            }

            .shomobay-app-shell .bg-white.shadow-sm.sm\:rounded-lg:hover,
            .shomobay-app-shell .bg-white.overflow-hidden.shadow-sm.sm\:rounded-lg:hover,
            .shomobay-app-shell .bg-white.p-6.shadow-sm.sm\:rounded-lg:hover,
            .shomobay-app-shell .bg-white.p-6.shadow-sm.rounded-lg:hover,
            .shomobay-app-shell .bg-white.shadow-sm.rounded-lg:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 42px rgba(20, 83, 45, 0.16);
            }

            .shomobay-app-shell main > .bg-gray-50.min-h-screen,
            .shomobay-app-shell main > .py-12.bg-gray-50.min-h-screen {
                background-color: transparent !important;
            }

            .shomobay-app-shell .text-gray-600 {
                color: rgb(51 65 85) !important;
            }

            .shomobay-app-shell .text-gray-500 {
                color: rgb(71 85 105) !important;
            }

            .shomobay-app-shell .text-gray-800 {
                color: rgb(15 23 42) !important;
            }

            .shomobay-app-shell input,
            .shomobay-app-shell select,
            .shomobay-app-shell textarea {
                background-color: rgba(255, 255, 255, 0.95);
            }
        </style>
    </head>

    <body class="font-sans antialiased text-gray-900">
        <div class="shomobay-app-shell">
            <div class="relative z-10 min-h-screen">
                @include('layouts.navigation')

                @isset($header)
                    <header class="shomobay-soft-header">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="shomobay-main-surface">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>