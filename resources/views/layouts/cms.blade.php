<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ View::yieldContent('page_title', config('app.name', 'Laravel')) }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ url('favicon.png') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    @include('partials.header')

    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('partials.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card" id="content-card">
                        <div class="card-body">
                            <div class="page-title">
                                <h1>@yield('page_title')</h1>
                                <p>@yield('page_subtitle')</p>
                            </div>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html> 