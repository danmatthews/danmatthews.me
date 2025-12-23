<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OG Image</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css'])
</head>
<body class="font-serif">
<div class="w-[1200px] h-[630px] overflow-hidden og-image">
    <div
        class="relative p-[60px] pr-[365px]  bg-white w-full h-full">

        <div class="h-full flex flex-col items-between justify-between">
            <div>
                <h1 class="text-5xl mb-6 leading font-serif">{{ $title }}</h1>
                @if(isset($excerpt) && $excerpt)
                    <p class="text-2xl text-gray-500 mb-8">{{ $excerpt }}</p>
                @endif
            </div>
            @isset($url)
            <div class="underline decoration-gray-300 underline-offset-4 text-subtle">
                {{ $url }}
            </div>
            @endisset
        </div>


        <img
            src="{{ asset('icons/favicon.svg') }}"
            alt="Logo"
            class="rounded-full h-[630px] w-[630px] absolute right-[-315px] top-0"
        />

    </div>
</body>

</html>
