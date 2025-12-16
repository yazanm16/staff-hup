<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Management')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">

    <div class="flex h-screen">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col">

            {{-- Navbar --}}
            @include('partials.navbar')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')

</body>

</html>
