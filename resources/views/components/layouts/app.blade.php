<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InBizTrack</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-50 text-slate-800">

    <header class="border-b bg-white">
        <div class="mx-auto max-w-4xl px-6 py-4">
            <a href="{{ route('projects.index') }}"
               class="text-xl font-bold text-indigo-700">
                InBizTrack
            </a>
            <span class="ml-2 text-sm text-slate-500">Project management</span>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-6 py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
