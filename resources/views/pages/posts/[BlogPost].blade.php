<?php

use function Laravel\Folio\name;

name('posts.show');
?>

<x-layouts.app
    title="{!! $blogPost->title !!}"
    :og_description="$blogPost->excerpt"
    :og_title="$blogPost->title"
    og_image="{{ asset('storage/opengraph/' . $blogPost->id . '.png') }}">
    <article class="article-styling max-w-3xl">
        <header class="">
           
            @if ($blogPost->months_ago >= 6)
            <div class="bg-gray-100 px-6 py-4 rounded mb-8">
                <h2 class="font-bold text-base">This article is more than 6 months old.</h2>
                <p class="text-sm">Please double check the validity of any information or code you find in this article as it may be out of date.</p>
            </div>
            @endif
            <x-page-title class="mb-4!">{{ $blogPost->title }}</x-page-title>
            <time datetime="{{ $blogPost->date }}"
                class="mb-16 text-base font-extrabold items-center text-gray-500 mb-4 w-full block">
              {{ $blogPost->date }}</time>
                
            </header>


            <div class="mt-8 article-body " data-mdx-content="true">
                {!! $blogPost->content !!}
            </div>
        </article>
    </div>


</x-layouts.app>
