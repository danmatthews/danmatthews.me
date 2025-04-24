<header
    class="relative z-40 py-4 border-b mb-8 flex justify-between items-center">
    <a href="{{ url('/') }}" class="flex items-center gap-4">
        <img alt="{{ config('app.name') }}" src="{{asset('images/wc-avatar-small.png')}}"
             class="size-9 rounded-full"/>
        <h1 class="font-bold">Dan Matthews</h1>
    </a>

    <nav class="pointer-events-auto relative flex gap-6 items-center">


        @foreach (config('site.navigation') as $item)

            <x-parts.nav-item :url="$item->url" :title="$item->title"/>

        @endforeach


    </nav>


</header>
