@props([
    'title' => null,
    'posts' => null,
])
@php

    @endphp
@if ($title)

    <x-page-title class="mb-12">{{$title}}</x-page-title>
@endif
<div class="max-w-3xl space-y-16">
    @forelse ($posts as $post)
        <article class="first:pt-0 group relative flex flex-col items-start space-y-6">

            <div class="space-y-2">
                <h2 class="text-base text-base font-semibold text-black dark:text-zinc-50">
                    <a href="{{ route('posts.show', ['blog_post' => $post]) }}"
                       class="">{{ $post->title }}</a>
                </h2>
                <time class="text-sm text-gray-500 dark:text-zinc-400 leading-none block"
                      datetime="{{ $post->date }}">
                    {{ $post->date->format('jS F Y') }}
                </time>
            </div>

            <p class="text-base text-gray-700 dark:text-zinc-400 ">
                {{ $post->excerpt }}
            </p>
            


        </article>
        @if (!$loop->last)
            {{-- <hr class="w-1/2" /> --}}
        @endif
    @empty
        Nothing here.
    @endforelse

    <div class="">
        {{ $posts->links() }}
    </div>

</div>


