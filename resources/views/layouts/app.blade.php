<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
        body {
            background-color: #f9fafb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1a202c;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-align: center;
        }

        form {
            background-color: #ffffff;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        input, button {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #cbd5e0;
            border-radius: 0.375rem;
        }

        button {
            background-color: #2d6cdf;
            color: white;
            font-weight: 600;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #1a56b3;
        }

        .error {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen antialiased">
    <div class="container mx-auto px-4 py-6">
        <!-- Navbar (optional) -->
        <header class="mb-6">
            <h1 class="text-2xl font-semibold">{{ config('app.name', 'Laravel') }}</h1>
        </header>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
