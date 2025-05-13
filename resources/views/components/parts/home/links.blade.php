@props([
    'title' => null,
    'links' => null,
])
@if ($title)
    <x-page-title class="mb-12">{{$title}}</x-page-title>
@endif
<div class="max-w-3xl grid grid-cols-1 gap-16">
    @forelse ($links as $link)
        <article class="first:pt-0 group relative flex flex-col items-start space-y-6">

            <div class="space-y-2">
                <h2 class="text-base text-base font-semibold text-black dark:text-zinc-50">
                    <a href="{{ $link->url }}" target="_blank"
                       class="">{{ $link->title }}</a>
                </h2>
                <p>{{ $link->url }}</p>
                <time class="text-sm text-gray-500 dark:text-zinc-400 leading-none block"
                      datetime="{{ $link->date }}">
                    {{ $link->date->format('jS F Y') }}
                </time>
            </div>

            <p class="text-base text-gray-700 dark:text-zinc-400 ">
                {{  strip_tags($link->description) }}
            </p>


        </article>
        @if (!$loop->last)
            {{-- <hr class="w-1/2" /> --}}
        @endif
    @empty
        Nothing here.
    @endforelse


</div>
<div class="max-w-3xl">
    {{ $links->links() }}
</div>


