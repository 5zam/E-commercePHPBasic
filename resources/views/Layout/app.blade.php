<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'World Store')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8B4513',
                        secondary: '#D2691E',
                        accent: '#F4A460'
                    }
                }
            }
        }
    </script>

    @stack('styles')
</head>

<body class="flex flex-col min-h-screen bg-amber-50">

    @include('layout.navbar')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layout.footer')

    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>

</html>