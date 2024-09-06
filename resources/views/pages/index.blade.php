<x-layouts.app title="Home" :avatar="false">
    <div class="mt-9">

        <x-parts.home.header />

        {{-- <x-parts.home.photos/> --}}

        <x-parts.home.articles />

        <div class="mt-24 md:mt-28">
            <div class="mx-auto grid max-w-xl grid-cols-1 gap-y-20 lg:max-w-none lg:grid-cols-2">
                <div class="space-y-10 lg:pl-16 xl:pl-24">


                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
