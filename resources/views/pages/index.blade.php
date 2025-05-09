@php
    $posts =  $posts = App\Models\BlogPost::orderBy('date', 'DESC')->simplePaginate(10);
    $page = request('page', 1);
    $title = $page == 1 ? 'Posts' : "Posts (Page {$page})";
@endphp
<x-layouts.app title="Posts">
    @if ($page == 1)


    <div class="use-heading-font font-medium max-w-3xl mb-12 underline-links-subtle leading-7">
        <p>Welcome to my little corner of the internet. I'm Dan Matthews, a full stack web developer
            living
            in
            <a href="https://www.google.com/maps/place/Carlisle/@54.9000249,-2.9780525,13z/data=!3m1!4b1!4m6!3m5!1s0x487ce1df3eee6b0f:0x5c0a43b6ba15682d!8m2!3d54.892473!4d-2.932931!16zL20vMGdqOTU?entry=ttu&g_ep=EgoyMDI1MDUwMy4wIKXMDSoASAFQAw%3D%3D" target="_blank">Carlisle, Cumbria.</a> I mostly blog about <a
                href="{{ route('tags.show', ['id' => 'laravel']) }}">Laravel</a>, <a
                href="{{ route('tags.show', ['id' => 'svelte']) }}">Svelte</a>, and <a
                href="https://vuejs.org/" target="_blank">VueJS</a>,
            but you can also find some more personal topics close to my heart here like cooking and more.</p>
    </div>
    @endif

    <div>
        <x-parts.home.articles :posts="$posts" :title="$title"/>
    </div>


</x-layouts.app>
