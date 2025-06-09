<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OG Image</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-red-400">
<div class="w-[1200px] h-[630px] overflow-hidden">
    <div
        class="relative p-[60px] pr-[365px]  bg-zinc-900 w-full h-full">

        <div>
            <h1 class="text-5xl font-bold text-white mb-6 leading">{{ $title }}</h1>
            @if(isset($excerpt) && $excerpt)
                <p class="text-2xl text-gray-300 mb-8">{{ $excerpt }}</p>
            @endif
        </div>

        <img
            src="{{ asset('icons/favicon.svg') }}"
            alt="Logo"
            class="rounded-full h-[630px] w-[630px] absolute right-[-315px] top-0"
        />

    </div>
</body>

</html>
