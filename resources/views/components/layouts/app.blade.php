@props([
    'title' => null,
    'avatar' => true,
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


    <link rel="apple-touch-icon" sizes="180x180" href="https://danmatthews.me/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://danmatthews.me/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://danmatthews.me/icons/favicon-16x16.png">
    <link rel="manifest" href="https://danmatthews.me/icons/site.webmanifest">

    @if (app()->environment('production'))
        <script defer type="text/javascript" src="https://api.pirsch.io/pirsch-extended.js" id="pirschextendedjs"
                data-code="CwNjQ2cCkJFwEIEQbsF3kNQIgo4xb17w"></script>
    @endif

    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#fff"/>
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#18181B"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex h-full bg-zinc-50 dark:bg-black" x-data="site">

<div class="flex w-full">
    <div class="fixed inset-0 flex justify-center sm:px-8">
        <div class="flex w-full max-w-7xl lg:px-8">
            <div class="w-full bg-white ring-1 ring-zinc-100 dark:bg-zinc-900 dark:ring-zinc-300/20"></div>
        </div>
    </div>
    <div class="relative flex w-full flex-col">
        <x-parts.header avatar="{{$avatar}}"/>
        <div class="flex-none" style="height:var(--content-offset)"></div>
        <main class="flex-auto">
            {{ $slot }}
        </main>
        <footer class="mt-32 flex-none">
            <div class="sm:px-8">
                <div class="mx-auto w-full max-w-7xl lg:px-8">
                    <div class="border-t border-zinc-100 pb-16 pt-10 dark:border-zinc-700/40">
                        <div class="relative px-4 sm:px-8 lg:px-12">
                            <div class="mx-auto max-w-2xl lg:max-w-5xl">
                                <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
                                    <div
                                        class="flex flex-wrap justify-center gap-x-6 gap-y-1 text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                        @foreach(config('site.navigation') as $item)
                                            <a class="transition hover:text-teal-500 dark:hover:text-teal-400"
                                               href="{{ url($item->url) }}">{{ $item->title }}</a>
                                        @endforeach
                                    </div>
                                    <p class="text-sm text-zinc-400 dark:text-zinc-500">©
                                        <?php date('Y') ?>
                                        Dan Matthews. All rights reserved.</p>
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
