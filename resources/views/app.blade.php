<!DOCTYPE html>
<html lang="en" class="h-full antialiased">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="description"
          content="Dan Matthews is a full stack PHP and JS developer based in Carlisle in the UK.">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow, max-video-preview:-1, max-image-preview:large, max-snippet:-1">
    <meta name="twitter:site" content="@danmatthews">
    <meta name="twitter:creator" content="@danmatthews">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#fff">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#151515">
    <link rel="icon" type="image/png" href="/icons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="/icons/favicon.svg">
    <link rel="shortcut icon" href="/icons/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
    <link rel="manifest" href="/icons/site.webmanifest">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Geist+Mono:wght@100..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @includeWhen(isset($postMeta), 'post-meta')

    @production
        <script defer type="text/javascript" src="https://api.pirsch.io/pirsch-extended.js" id="pirschextendedjs"
                data-code="CwNjQ2cCkJFwEIEQbsF3kNQIgo4xb17w"></script>
    @endproduction

    <x-feed-links/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="h-full bg-slate-50 text-slate-900">
@inertia
</body>
</html>
