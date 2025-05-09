<header
    class="relative z-40 py-12 mb-8 flex justify-between items-center">
    <a href="{{ url('/') }}" class="flex items-center gap-4">
        <h1 class="font-semibold text-black text-xl ">Dan Matthews</h1>
    </a>

    <nav class="pointer-events-auto relative gap-6 items-center hidden md:flex">

        @foreach (config('site.navigation') as $item)
            @php $active = is_callable($item->isActive) ? ($item->isActive)(request()) : null; @endphp
            <x-parts.nav-item :url="$item->url" :title="$item->title" :active="$active"/>

        @endforeach

    </nav>

    <button class="block md:hidden cursor-pointer" @click="mobileMenuOpen = !mobileMenuOpen">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
            <path fill-rule="evenodd"
                  d="M2 4.75A.75.75 0 0 1 2.75 4h10.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75Zm0 6.5a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z"
                  clip-rule="evenodd"/>
        </svg>
    </button>


</header>


