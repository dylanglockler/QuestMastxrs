@extends('layouts.app')

@section('content')
    <section class="paper-card torn-edge rounded-lg px-6 py-10 text-center sm:px-10">
        <h1 class="font-display text-3xl leading-tight text-ink-900 sm:text-4xl">
            Free treasure hunts, hidden in plain sight.
        </h1>
        <p class="mx-auto mt-4 max-w-xl text-ink-700">
            We stash laminated clues around town — parks, alleys, that bench nobody sits on.
            Solve one, it points to the next. No app, no fee, no map with an X.
            Just you, some strangers, and a good excuse to leave the house.
        </p>
    </section>

    <section class="mt-10">
        <h2 class="font-display text-lg text-rust-600">Hunts underway</h2>

        @if ($hunts->isEmpty())
            <p class="mt-4 text-ink-700/70">
                Nothing's live right now — the hosts are out laminating. Check back soon.
            </p>
        @else
            <div class="mt-4 grid gap-5 sm:grid-cols-2">
                @foreach ($hunts as $hunt)
                    <a href="{{ route('hunts.show', $hunt) }}" class="paper-card group block rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
                        @if ($hunt->cover_image)
                            <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($hunt->cover_image) }}" alt="" class="mb-4 h-32 w-full rounded object-cover">
                        @endif
                        <h3 class="font-display text-xl text-ink-900 group-hover:text-rust-600">{{ $hunt->title }}</h3>
                        @if ($hunt->tagline)
                            <p class="mt-1 text-sm italic text-ink-700/80">{{ $hunt->tagline }}</p>
                        @endif
                        <p class="mt-3 text-xs uppercase tracking-widest text-forest-700">
                            {{ $hunt->neighborhood ? "{$hunt->neighborhood}, " : '' }}{{ $hunt->city }}
                        </p>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
