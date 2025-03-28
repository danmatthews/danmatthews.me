<x-layouts.app title="Projects">

    <div class="sm:px-8 mt-16 sm:mt-32">
        <div class="mx-auto w-full max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <header class="max-w-2xl">
                        <h1 class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100">
                            Here are some of the little things i've made.</h1>
                        <p class="mt-6 text-base text-zinc-600 dark:text-zinc-400">I'm actively trying to increase my
                            contributions to open source through Intrfce - my vendor name for Laravel and open source
                            packages, but there are also some bits and bobs here that i'm especially proud of.</p>
                    </header>
                    <div class="mt-16 sm:mt-20">
                        <ul role="list" class="grid grid-cols-1 gap-x-12 gap-y-16 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach (config('site.projects') as $project)
                                <li class="group relative flex flex-col items-start">
                                    <div
                                        class="relative z-10 flex h-12 w-12 items-center justify-center rounded-xs bg-orange shadow-md shadow-zinc-800/5 ring-1 ring-zinc-900/5  dark:ring-0">
                                        @if (!empty($project->icon))
                                            <img alt="" loading="lazy" width="32" height="32"
                                                decoding="async" data-nimg="1" class="h-8 w-8"
                                                src="/_next/static/media/planetaria.ecd81ade.svg"
                                            style="color: transparent;"=@else <svg fill="none"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-8">
                                            <path d="M5 5h16v10H7V9h10v2H9v2h10V7H5v10h14v2H3V5h2z"
                                                fill="currentColor" /> </svg>
                                        @endif
                                    </div>
                                    <h2 class="mt-6 text-base font-semibold text-zinc-800 dark:text-zinc-100">
                                        <div
                                            class="absolute -inset-x-4 -inset-y-6 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 sm:-inset-x-6 sm:rounded-2xl dark:bg-zinc-800/50">
                                        </div>
                                        <a href="{{ $project->url }}"><span
                                                class="absolute -inset-x-4 -inset-y-6 z-20 sm:-inset-x-6 sm:rounded-2xl"></span><span
                                                class="relative z-10">{{ $project->title }}</span></a>
                                    </h2>
                                    <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $project->description }}</p>
                                    <p
                                        class="relative z-10 mt-6 flex text-sm font-medium text-zinc-400 transition group-hover:text-orange dark:text-zinc-200">
                                        <svg viewBox="0 0 24 24" aria-hidden="true" class="h-6 w-6 flex-none">
                                            <path
                                                d="M15.712 11.823a.75.75 0 1 0 1.06 1.06l-1.06-1.06Zm-4.95 1.768a.75.75 0 0 0 1.06-1.06l-1.06 1.06Zm-2.475-1.414a.75.75 0 1 0-1.06-1.06l1.06 1.06Zm4.95-1.768a.75.75 0 1 0-1.06 1.06l1.06-1.06Zm3.359.53-.884.884 1.06 1.06.885-.883-1.061-1.06Zm-4.95-2.12 1.414-1.415L12 6.344l-1.415 1.413 1.061 1.061Zm0 3.535a2.5 2.5 0 0 1 0-3.536l-1.06-1.06a4 4 0 0 0 0 5.656l1.06-1.06Zm4.95-4.95a2.5 2.5 0 0 1 0 3.535L17.656 12a4 4 0 0 0 0-5.657l-1.06 1.06Zm1.06-1.06a4 4 0 0 0-5.656 0l1.06 1.06a2.5 2.5 0 0 1 3.536 0l1.06-1.06Zm-7.07 7.07.176.177 1.06-1.06-.176-.177-1.06 1.06Zm-3.183-.353.884-.884-1.06-1.06-.884.883 1.06 1.06Zm4.95 2.121-1.414 1.414 1.06 1.06 1.415-1.413-1.06-1.061Zm0-3.536a2.5 2.5 0 0 1 0 3.536l1.06 1.06a4 4 0 0 0 0-5.656l-1.06 1.06Zm-4.95 4.95a2.5 2.5 0 0 1 0-3.535L6.344 12a4 4 0 0 0 0 5.656l1.06-1.06Zm-1.06 1.06a4 4 0 0 0 5.657 0l-1.061-1.06a2.5 2.5 0 0 1-3.535 0l-1.061 1.06Zm7.07-7.07-.176-.177-1.06 1.06.176.178 1.06-1.061Z"
                                                fill="currentColor"></path>
                                        </svg>
                                        <span class="ml-2">{{ $project->url }}</span>
                                    </p>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
