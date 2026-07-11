@extends('layouts.app')

@section('content')
    <header class="paper-card torn-edge rounded-lg px-6 py-8 sm:px-10">
        <p class="text-xs uppercase tracking-widest text-forest-700">
            {{ $hunt->neighborhood ? "{$hunt->neighborhood}, " : '' }}{{ $hunt->city }}
        </p>
        <h1 class="mt-2 font-display text-3xl text-ink-900">{{ $hunt->title }}</h1>
        @if ($hunt->tagline)
            <p class="mt-2 italic text-ink-700/80">{{ $hunt->tagline }}</p>
        @endif
        @if ($hunt->description)
            <p class="mt-4 text-ink-700">{{ $hunt->description }}</p>
        @endif
        @if ($hunt->starting_hint)
            <div class="mt-6 rounded border-2 border-dashed border-rust-600/50 bg-parchment-100 px-4 py-3">
                <p class="text-xs font-bold uppercase tracking-widest text-rust-600">Where to start</p>
                <p class="mt-1 text-ink-800">{{ $hunt->starting_hint }}</p>
            </div>
        @endif
    </header>

    <section class="mt-10 space-y-8">
        @forelse ($hunt->clues as $clue)
            <article id="clue-{{ $clue->id }}" class="paper-card rounded-lg p-6 scroll-mt-6" x-data="{ revealed: [false, false, false] }">
                <div class="flex items-baseline gap-3">
                    <span class="font-display text-sm text-rust-600">Clue {{ $clue->order }}</span>
                    @if ($clue->title)
                        <span class="text-xs uppercase tracking-widest text-ink-700/50">{{ $clue->title }}</span>
                    @endif
                </div>

                <p class="mt-3 font-display text-lg leading-relaxed text-ink-900">{{ $clue->riddle_text }}</p>

                @if ($clue->location_note)
                    <p class="mt-3 text-sm text-ink-700/70">{{ $clue->location_note }}</p>
                @endif

                @if ($clue->hints->isNotEmpty())
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach ($clue->hints as $i => $hint)
                            <button
                                type="button"
                                x-show="!revealed[{{ $i }}]"
                                @click="revealed[{{ $i }}] = true"
                                class="rounded-full border border-forest-700/40 px-3 py-1 text-xs font-semibold text-forest-700 hover:bg-forest-700 hover:text-parchment-50"
                            >
                                Reveal hint {{ $i + 1 }}
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-3 space-y-2">
                        @foreach ($clue->hints as $i => $hint)
                            <p x-show="revealed[{{ $i }}]" x-cloak class="rounded bg-parchment-100 px-3 py-2 text-sm text-ink-800">
                                <span class="font-semibold text-rust-600">Hint {{ $i + 1 }}:</span> {{ $hint->text }}
                            </p>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6 border-t border-ink-900/10 pt-5">
                    <h3 class="font-display text-sm text-forest-700">Fellow questers</h3>

                    @if ($clue->messages->isEmpty())
                        <p class="mt-2 text-sm text-ink-700/60">No notes yet — be the first to help out.</p>
                    @else
                        <ul class="mt-3 space-y-3">
                            @foreach ($clue->messages as $message)
                                <li class="rounded bg-parchment-100 px-3 py-2 text-sm">
                                    <span class="font-semibold text-ink-800">{{ $message->nickname }}</span>
                                    <span class="ml-2 text-xs text-ink-700/50">{{ $message->created_at->diffForHumans() }}</span>
                                    <p class="mt-1 text-ink-700">{{ $message->body }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('clues.messages.store', ['hunt' => $hunt, 'clue' => $clue]) }}" class="mt-4 space-y-2">
                        @csrf
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <input
                                type="text"
                                name="nickname"
                                placeholder="Nickname"
                                value="{{ old('nickname', request()->cookie('quester_nickname')) }}"
                                maxlength="255"
                                required
                                class="w-full rounded border border-ink-900/20 bg-parchment-50 px-3 py-2 text-sm sm:w-40"
                            >
                            <input
                                type="text"
                                name="body"
                                placeholder="Leave a tip for other questers…"
                                maxlength="2000"
                                required
                                class="w-full rounded border border-ink-900/20 bg-parchment-50 px-3 py-2 text-sm"
                            >
                            <button type="submit" class="shrink-0 rounded bg-forest-700 px-4 py-2 text-sm font-semibold text-parchment-50 hover:bg-forest-800">
                                Post
                            </button>
                        </div>
                        @error('nickname')
                            <p class="text-xs text-rust-700">{{ $message }}</p>
                        @enderror
                        @error('body')
                            <p class="text-xs text-rust-700">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </article>
        @empty
            <p class="text-ink-700/70">Clues are still being laminated. Check back soon.</p>
        @endforelse
    </section>

    <section id="photos" class="mt-12 paper-card rounded-lg p-6 scroll-mt-6">
        <h2 class="font-display text-lg text-rust-600">Victory wall</h2>
        <p class="mt-1 text-sm text-ink-700/70">Finished the hunt? Drop a photo to celebrate.</p>

        @if ($hunt->photos->isNotEmpty())
            <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach ($hunt->photos as $photo)
                    <figure class="overflow-hidden rounded border border-ink-900/10">
                        <img src="{{ $photo->url() }}" alt="{{ $photo->caption }}" class="h-32 w-full object-cover">
                        <figcaption class="bg-parchment-100 px-2 py-1 text-xs text-ink-700">
                            <span class="font-semibold">{{ $photo->nickname }}</span>
                            @if ($photo->caption)
                                — {{ $photo->caption }}
                            @endif
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hunts.photos.store', $hunt) }}" enctype="multipart/form-data" class="mt-6 space-y-3">
            @csrf
            <div class="flex flex-col gap-2 sm:flex-row">
                <input
                    type="text"
                    name="nickname"
                    placeholder="Nickname"
                    value="{{ old('nickname', request()->cookie('quester_nickname')) }}"
                    maxlength="255"
                    required
                    class="w-full rounded border border-ink-900/20 bg-parchment-50 px-3 py-2 text-sm sm:w-40"
                >
                <input
                    type="text"
                    name="caption"
                    placeholder="Caption (optional)"
                    maxlength="255"
                    class="w-full rounded border border-ink-900/20 bg-parchment-50 px-3 py-2 text-sm"
                >
            </div>
            <input type="file" name="image" accept="image/*" required class="text-sm">
            @error('image')
                <p class="text-xs text-rust-700">{{ $message }}</p>
            @enderror
            <button type="submit" class="rounded bg-rust-600 px-4 py-2 text-sm font-semibold text-parchment-50 hover:bg-rust-700">
                Post photo
            </button>
        </form>
    </section>
@endsection
