<?php

use function Laravel\Folio\name;

name('posts.show');
?>

<x-layouts.app
    title="{!! $blogPost->title !!}"
    :og_description="$blogPost->excerpt"
    :og_title="$blogPost->title"
    og_image="{{ asset('storage/opengraph/' . $blogPost->id . '.png') }}">


    <div class="max-w-3xl">

        <article class="article-styling">
            <header class="flex flex-col space-y-4">
                <h1
                    class="text-4xl font-extrabold">
                    {{ $blogPost->title }}</h1>

                <time datetime="{{ $blogPost->date }}"
                      class="flex  text-sm items-center text-gray-600">
                    <span
                        class="">{{ $blogPost->date }}</span></time>
            </header>


            <div class="mt-8 article-body " data-mdx-content="true">
                {!! $blogPost->content !!}
            </div>
        </article>
    </div>


</x-layouts.app>
