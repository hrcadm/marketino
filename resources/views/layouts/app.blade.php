<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Flatpicr date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

    <!-- Tooltips -->
    <link rel="stylesheet" href="{{ asset('css/hint.css') }}">


    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <livewire:styles/>
    @stack('styles')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.6.0/dist/alpine.js" defer></script>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    @include('components.notification')
    <!-- Navigation -->
    <livewire:navigation/>

    <!-- Page Heading -->
    @isset($header)
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                        {{ $header }}
                    </h2>
                </div>
                {{ $actions ?? '' }}
            </div>
        </div>
    </header>
    @endisset

    <!-- Page Content -->
    <main>
        <div class="py-12">
            @if(Request::path() === 'billing/quotes')
            <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 86rem">
            @else
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @endif
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    @isset($card_header)
                        <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                            {{ $card_header }}
                        </div>
                    @endisset

                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>
</div>
@stack('modals')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<livewire:scripts/>
@stack('scripts')
</body>
</html>
