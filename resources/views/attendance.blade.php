<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite('resources/js/face-api.js')
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="container mx-auto p-4">
                <h1 class="text-2xl font-bold">Attendance</h1>
                <div class="mt-4" style="position: relative; display: flex; justify-content: center;">
                    <video id="video" width="720" height="560" autoplay muted></video>
                    <canvas id="canvas" width="720" height="560" style="position: absolute;"></canvas>
                </div>
                <div class="mt-4">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="border border-gray-300">Name</th>
                                <th class="border border-gray-300">Time</th>
                            </tr>
                        </thead>
                        <tbody id="attendance"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
