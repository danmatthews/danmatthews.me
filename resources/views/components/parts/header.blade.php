@props([
    'avatar' => false,
])
<header class="pointer-events-none relative z-40 flex flex-none flex-col"
    style="height:var(--header-height);margin-bottom:var(--header-mb)">
    <div class="order-last mt-[calc(theme(spacing.16)-theme(spacing.3))]"></div>
    <div class="sm:px-8 top-0 order-last -mb-3 pt-3" style="position:var(--header-position)">
        <div class="mx-auto w-full max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <div class="top-[var(--avatar-top,theme(spacing.3))] w-full"
                        style="position:var(--header-inner-position)">
                        <div class="relative">
                            <div class="absolute left-0 top-3 origin-left transition-opacity h-10 w-10 rounded-full bg-white/90 p-0.5 shadow-lg shadow-zinc-800/5 ring-1 ring-zinc-900/5 backdrop-blur dark:bg-zinc-800/90 dark:ring-white/10"
                                style="opacity:var(--avatar-border-opacity, 0);transform:var(--avatar-border-transform)">
                            </div>
                            @if (!$avatar)
                                <a aria-label="Home" class="block h-16 w-16 origin-left pointer-events-auto"
                                    style="transform:var(--avatar-image-transform)" href="/">
                                    <img src="{{ asset('images/avatar-small.png') }}" alt="{{ config('app.name') }}"
                                        fetchpriority="high" width="512" height="512" decoding="async"
                                        data-nimg="1"
                                        class="rounded bg-zinc-100 object-cover dark:bg-zinc-800 h-16 w-16"
                                        style="color:transparent" sizes="4rem"></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="top-0 z-10 h-16 pt-6" style="position:var(--header-position)">
        <div class="sm:px-8 top-[var(--header-top,theme(spacing.6))] w-full"
            style="position:var(--header-inner-position)">
            <div class="mx-auto w-full max-w-7xl lg:px-8">
                <div class="relative px-4 sm:px-8 lg:px-12">
                    <div class="mx-auto max-w-2xl lg:max-w-5xl">
                        <div class="relative flex gap-4">
                            <div class="flex flex-1">
                                @if ($avatar)
                                    <div
                                        class="h-10 w-10 rounded-full bg-white/90 p-0.5 shadow-lg shadow-zinc-800/5 ring-1 ring-zinc-900/5 backdrop-blur dark:bg-zinc-800/90 dark:ring-white/10">

                                        <a aria-label="Home" class="pointer-events-auto" href="/"><img
                                                alt="" fetchpriority="high" width="512" height="512"
                                                decoding="async" data-nimg="1"
                                                class=" bg-zinc-100 object-cover dark:bg-zinc-800 h-9 w-9"
                                                sizes="2.25rem" src="{{ asset('images/avatar-small.png') }}"
                                                style="color: transparent;"></a>
                                    </div>
                                @endif

                            </div>
                            <div class="flex flex-1 justify-end md:justify-center">
                                <div class="pointer-events-auto md:hidden">
                                    <button
                                        class="group flex items-center rounded-full bg-white/90 px-4 py-2 text-sm font-medium text-zinc-800 shadow-lg shadow-zinc-800/5 ring-1 ring-zinc-900/5 backdrop-blur dark:bg-zinc-800/90 dark:text-zinc-200 dark:ring-white/10 dark:hover:ring-white/20"
                                        type="button" aria-expanded="false"
                                        @click.prevent="mobileMenuOpen = !mobileMenuOpen">Menu
                                        <svg viewBox="0 0 8 6" aria-hidden="true"
                                            class="ml-3 h-auto w-2 stroke-zinc-500 group-hover:stroke-zinc-700 dark:group-hover:stroke-zinc-400">
                                            <path d="M1.75 1.75 4 4.25l2.25-2.5" fill="none" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div hidden=""
                                    style="position:fixed;top:1px;left:1px;width:1px;height:0;padding:0;margin:-1px;overflow:hidden;clip:rect(0, 0, 0, 0);white-space:nowrap;border-width:0;display:none">
                                </div>
                                <nav class="pointer-events-auto hidden md:block relative">
                                    {{-- @include('components.parts.box-decorator-orange') --}}
                                    <ul
                                        class="flex  px-3 text-sm font-medium text-zinc-800 shadow-lg shadow-zinc-800/5 ring-1 ring-zinc-900/5 backdrop-blur dark:bg-zinc-800/90 dark:text-zinc-200 dark:ring-white/10">
                                        @foreach (config('site.navigation') as $item)
                                            <li>
                                                <a class="relative font-bold block px-3 py-2 transition hover:text-orange dark:hover:text-orange"
                                                    href="{{ url($item->url) }}">{{ $item->title }}</a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </nav>


                            </div>
                            <div class="flex justify-end md:flex-1">
                                <div class="flex gap-1 hidden">
                                    <div class="size-8 bg-white rounded-sm">
                                        <span class="p-1">A</span>
                                    </div>
                                    <div class="size-8 bg-zinc-600 hover:bg-white rounded-sm">
                                        <span class="p-1 font-sans">A</span>
                                    </div>
                                </div>
                                <div class="pointer-events-auto">
                                    <button type="button" aria-label="Switch to light theme"
                                        class="hidden group rounded-full bg-white/90 px-3 py-2 shadow-lg shadow-zinc-800/5 ring-1 ring-zinc-900/5 backdrop-blur transition dark:bg-zinc-800/90 dark:ring-white/10 dark:hover:ring-white/20">
                                        <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true"
                                            class="h-6 w-6 fill-zinc-100 stroke-zinc-500 transition group-hover:fill-zinc-200 group-hover:stroke-zinc-700 dark:hidden [@media(prefers-color-scheme:dark)]:fill-orange [@media(prefers-color-scheme:dark)]:stroke-teal-500 [@media(prefers-color-scheme:dark)]:group-hover:fill-teal-50 [@media(prefers-color-scheme:dark)]:group-hover:stroke-teal-600">
                                            <path
                                                d="M8 12.25A4.25 4.25 0 0 1 12.25 8v0a4.25 4.25 0 0 1 4.25 4.25v0a4.25 4.25 0 0 1-4.25 4.25v0A4.25 4.25 0 0 1 8 12.25v0Z">
                                            </path>
                                            <path
                                                d="M12.25 3v1.5M21.5 12.25H20M18.791 18.791l-1.06-1.06M18.791 5.709l-1.06 1.06M12.25 20v1.5M4.5 12.25H3M6.77 6.77 5.709 5.709M6.77 17.73l-1.061 1.061"
                                                fill="none"></path>
                                        </svg>
                                        <svg viewBox="0 0 24 24" aria-hidden="true"
                                            class="hidden h-6 w-6 fill-zinc-700 stroke-zinc-500 transition dark:block [@media(prefers-color-scheme:dark)]:group-hover:stroke-zinc-400 [@media_not_(prefers-color-scheme:dark)]:fill-orange/10 [@media_not_(prefers-color-scheme:dark)]:stroke-orange">
                                            <path
                                                d="M17.25 16.22a6.937 6.937 0 0 1-9.47-9.47 7.451 7.451 0 1 0 9.47 9.47ZM12.75 7C17 7 17 2.75 17 2.75S17 7 21.25 7C17 7 17 11.25 17 11.25S17 7 12.75 7Z"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
