<?php

use function Laravel\Folio\name;

name('tags.index');


?>
@php
    $tags = App\Models\PostTag::get()->groupBy('tag');
@endphp
<x-layouts.app title="Post Tags">
    <x-page-title class="mb-12">Post Tags</x-page-title>
    <div class="space-y-4">
        @foreach ($tags as $tag => $posts)
            <a class="block" href="{{ route('tags.show', ['id' => $tag]) }}">
                {{$tag}} <span class="text-gray-500">({{ $posts->count()  }})</span>
            </a>
        @endforeach
    </div>

</x-layouts.app>
