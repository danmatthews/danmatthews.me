<x-layouts.app title="Work">
    <div class=" mb-8">
        <h1 class="text-4xl font-extrabold">Work</h1>
    </div>
    <div class="max-w-2xl mb-8">
        <p class="mt-6 text-base">I'm actively trying to increase my
            contributions to open source through <strong>Intrfce</strong> - my vendor name for Laravel and open source
            packages, but there are also some bits and bobs here that i'm especially proud of.</p>
    </div>

    <ul role="list" class="divide-y">
        @foreach (config('site.projects') as $project)
            <li class="group relative grid grid-cols-1 md:grid-cols-3 gap-8 items-center py-6">
                <div class="col-span-2">
                    <h2 class="text-base font-bold">
                        <a href="{{ $project->url }}">{{ $project->title }}</a>
                    </h2>
                    <p class="relative z-10 mt-2 text-sm ">
                        {{ $project->description }}</p>
                </div>
                <div class="flex items-center sm:justify-end md:justify-start">
                    <a
                        href="{{$project->url}}"
                        class="bg-gray-100 text-sm px-3 py-2 rounded-full hover:bg-black hover:text-white"
                        target="_blank"
                    >

                        {{ $project->button_text }}
                    </a>
                </div>
            </li>
        @endforeach

    </ul>


</x-layouts.app>
