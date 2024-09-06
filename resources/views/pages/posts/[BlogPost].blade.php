<?php

use function Laravel\Folio\name;

name('posts.show');
?>

<x-layouts.app title="{{ $blogPost->title }}" og_description="{{ $blogPost->excerpt }}" og_title="{{ $blogPost->title }}"
    og_image="{{ asset('storage/opengraph/' . $blogPost->id . '.png') }}">

    <div class="sm:px-8 mt-16 lg:mt-32">
        <div class="mx-auto w-full max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <div class="xl:relative">
                        <div class="mx-auto max-w-2xl">
                            <a type="button" aria-label="Go back to articles" href="{{ url('posts') }}"
                                class="rounded-sm filter-none group mb-8 flex h-10 w-10 items-center justify-center bg-orange shadow-md shadow-zinc-800/5 ring-1 ring-zinc-900/5 transition lg:absolute lg:-left-5 lg:-mt-2 lg:mb-0 xl:-top-1.5 xl:left-0 xl:mt-0  ">
                                <svg width="24" height="24"
                                    class="h-6 w-6  transition text-[#151515] stroke-[#151515] fill-current"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M20 11L20 13L8 13L8 15L6 15L6 13L4 13L4 11L6 11L6 9L8 9L8 11L20 11ZM10 7L8 7L8 9L10 9L10 7ZM10 7L12 7L12 5L10 5L10 7ZM10 17L8 17L8 15L10 15L10 17ZM10 17L12 17L12 19L10 19L10 17Z" />
                                </svg>


                            </a>
                            <article>
                                <header class="flex flex-col">
                                    <h1
                                        class="mt-6 text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100">
                                        {{ $blogPost->title }}</h1>
                                    <time datetime="{{ $blogPost->date }}"
                                        class="order-first flex items-center text-base text-zinc-400 dark:text-zinc-500">
                                        <span class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span><span
                                            class="ml-3">{{ $blogPost->date }}</span></time>
                                </header>
                                <div class="mt-8 prose dark:prose-invert" data-mdx-content="true">
                                    {!! $blogPost->content !!}
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
