<x-layouts.app title="Work">
    <x-page-title class="mb-16">Work</x-page-title>


    <ul role="list" class="space-y-16">
        @foreach (config('site.projects') as $project)
            <li class="group relative grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <div class="col-span-2">
                    <h2 class="text-base font-semibold">
                        <a href="{{ $project->url }}">{{ $project->title }}</a>
                    </h2>
                    <p class="relative z-10 mt-2 text-sm leading-6 text-gray-600 dark:text-zinc-400 ">
                        {{ $project->description }}</p>
                </div>
                <div class="flex items-center sm:justify-end md:justify-start">
                    <a
                        href="{{$project->url}}"
                        class="bg-gray-100 dark:bg-zinc-800 text-sm px-3 py-2 rounded-full hover:bg-black hover:text-white"
                        target="_blank"
                    >

                        {{ $project->button_text }}
                    </a>
                </div>
            </li>
        @endforeach

    </ul>


</x-layouts.app>
