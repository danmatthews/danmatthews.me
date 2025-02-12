@props([
    'title' => null,
    'avatar' => true,
    'og_title' => null,
    'og_image' => null,
    'og_description' => null,
])
<html lang="en" class="h-full antialiased dark">

<head>

    <title>{{ $title ?? null }} | {{ config('app.name') }}</title>
    <meta charSet="utf-8"/>

    <meta name="description" content="Dan Matthews is a full stack PHP and JS developer based in Carlisle in the UK."/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="robots" content="index, follow"/>
    <meta name="googlebot" content="index, follow, max-video-preview:-1, max-image-preview:large, max-snippet:-1"/>
    <link rel="canonical" href="http://danmatthews.me"/>


    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('icons/site.webmanifest') }}">

    @if (app()->environment('production'))
        <script defer type="text/javascript" src="https://api.pirsch.io/pirsch-extended.js" id="pirschextendedjs"
                data-code="CwNjQ2cCkJFwEIEQbsF3kNQIgo4xb17w"></script>
    @endif

    <meta name="twitter:site" content="@danmatthews">
    <meta name="twitter:creator" content="@">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:site_name" content="danmatthews.me"/>
    @isset($og_title)
        <meta property="og:type" content="article"/>
        <meta property="og:title" content="{{ $og_title }}"/>
        <meta name="twitter:title" content="{{ $og_title }}">
    @endisset
    @isset($og_description)
        <meta property="og:description" content="{{ $og_description }}"/>
        <meta name="twitter:description" content="{{ $og_description }}">
    @endisset
    @isset($og_image)
        <meta property="og:image" content="{{ $og_image }}"/>
        <meta name="twitter:image" content="{{ $og_image }}">
    @endisset
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#fff"/>
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#18181B"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js" integrity="sha512-7Z9J3l1+EYfeaPKcGXu3MS/7T+w19WtKQY/n+xzmw4hZhJ9tyYmcUS+4QqAlzhicE5LAfMQSF3iFTK9bQdTxXg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js" integrity="sha512-SkmBfuA2hqjzEVpmnMt/LINrjop3GKWqsuLSSB3e7iBmYK7JuWw4ldmmxwD9mdm2IRTTi0OxSAfEGvgEi0i2Kw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="{{asset('js/prism-svelte.js') }}"></script>


    
</head>

<body class="flex h-full bg-zinc-100 dark:bg-[#151515]" x-data="site">

<div class="flex w-full">

    <div x-show="mobileMenuOpen"
         class="fixed inset-0 z-50 bg-zinc-800/40 backdrop-blur-xs duration-150 data-closed:opacity-0 data-enter:ease-out data-leave:ease-in dark:bg-black/80"
         aria-hidden="true"></div>

    <div x-show="mobileMenuOpen" x-cloak
         class="fixed inset-x-4 top-8 z-50 origin-top rounded-3xl bg-white p-8 ring-1 ring-zinc-900/5 duration-150 data-closed:scale-95 data-closed:opacity-0 data-enter:ease-out data-leave:ease-in dark:bg-zinc-900 dark:ring-zinc-800"
         tabindex="-1" style="--button-width: 88.671875px;">
        <div class="flex flex-row-reverse items-center justify-between">
            <button @click.prevent="mobileMenuOpen = !mobileMenuOpen" aria-label="Close menu" class="-m-1 p-1"
                    type="button">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-6 w-6 text-zinc-500 dark:text-zinc-400">
                    <path d="m17.25 6.75-10.5 10.5M6.75 6.75l10.5 10.5" fill="none" stroke="currentColor"
                          stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
            <h2 class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Navigation</h2>
        </div>
        <nav class="mt-6">
            <ul
                class="-my-2 divide-y divide-zinc-100 text-base text-zinc-800 dark:divide-zinc-100/5 dark:text-zinc-300">
             
                @foreach (config('site.navigation') as $item)
                    <li>
                        <a class="block py-2" href="{{ url($item->url) }}">{{ $item->title }}</a>
                    </li>
                @endforeach

            </ul>
        </nav>
    </div>
    <div class="relative flex w-full flex-col">
        <x-parts.header avatar="{{ $avatar }}"/>
        <div class="flex-none" style="height:var(--content-offset)"></div>
        <main class="flex-auto">
            {{ $slot }}
        </main>
        <footer class="mt-32 flex-none">
            <div class="sm:px-8">
                <div class="mx-auto w-full max-w-7xl lg:px-8">
                    <div class="border-t border-zinc-100 pb-16 pt-10 dark:border-zinc-700/40 relative">

                        <div class="relative px-4 sm:px-8 lg:px-12">
                            <div class="mx-auto max-w-2xl lg:max-w-5xl">
                                <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
                                    <div
                                        class="flex flex-wrap justify-center gap-x-6 gap-y-1 text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                        @foreach (config('site.navigation') as $item)
                                            <a class="transition hover:text-orange dark:hover:text-orange"
                                               href="{{ url($item->url) }}">{{ $item->title }}</a>
                                        @endforeach
                                    </div>
                                    <div class="flex flex-col gap-3 items-end">
                                    <p class="text-sm text-zinc-400 dark:text-zinc-500">©
                                        <?php date('Y'); ?>
                                        Dan Matthews. All rights reserved.</p>
                                    <div class="flex gap-3 items-center">
<p class="text-xs text-zinc-400 dark:text-zinc-500">Hosted on Laravel Cloud</p>
                                        <svg class="size-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M10.258 3.175 5.4.475l-4.858 2.7V18.38L9.8 23.524l9.258-5.143v-4.89l4.4-2.444V5.62L18.6 2.92l-4.859 2.7v4.888l-3.483 1.935zm4.4 7.333v-3.84l3.484 1.935v3.84zm-9.716 5.428 4.4 2.445v3.84l-7.884-4.38V4.223l3.484 1.935z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

</body>

</html>
