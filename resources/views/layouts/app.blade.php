<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Questmastxrs' }} — Free public treasure hunts</title>
    <meta name="description" content="Free, real-world treasure hunts hidden around town. Follow the clues, crack the riddles, meet your neighbors.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans text-ink-900 antialiased">
    <header class="border-b-2 border-ink-900/10 bg-parchment-50/70">
        <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-baseline gap-2">
                <span class="font-display text-xl text-rust-600">Questmastxrs</span>
                <span class="hidden text-xs uppercase tracking-widest text-ink-700/60 sm:inline">est. this basement</span>
            </a>
            <a href="{{ route('home') }}" class="text-sm font-semibold text-forest-700 hover:text-forest-800">
                All hunts →
            </a>
        </div>
    </header>

    @if (session('status'))
        <div class="mx-auto mt-4 max-w-4xl px-4 sm:px-6">
            <div class="paper-card rounded-lg px-4 py-3 text-sm font-semibold text-forest-700">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6">
        @yield('content')
    </main>

    <footer class="mt-16 border-t-2 border-ink-900/10 py-8 text-center text-xs text-ink-700/60">
        Made for wandering. No accounts, no ads, no map to X marks the spot — just clues and neighbors.
    </footer>
</body>
</html>
