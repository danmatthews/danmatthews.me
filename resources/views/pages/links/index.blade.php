@php
    $posts =  $posts = App\Models\BlogLink::orderBy('date', 'DESC')->simplePaginate(10);
    $page = request('page', 1);
    $title = $page == 1 ? 'Links' : "Posts (Page {$page})";
@endphp
<x-layouts.app title="Links">

    <div>
        <x-parts.home.links :links="$posts" :title="$title"/>
    </div>


</x-layouts.app>
