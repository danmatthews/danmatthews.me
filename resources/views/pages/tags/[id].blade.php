<?php

use function Laravel\Folio\name;

name('tags.show');


?>
@php
    $posts = App\Models\BlogPost::orderBy('date', 'DESC')->whereHas('tags', fn($query) => $query->where('tag', $id))->paginate(10);
@endphp
<x-layouts.app title="Posts tagged with '{{$id}}'">
    <div>
        <x-parts.home.articles :posts="$posts" title="Posts tagged with '{{$id}}'"/>
    </div>

</x-layouts.app>
