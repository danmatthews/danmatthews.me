<div class="mt-16 sm:mt-20">
    <div class="-my-4 flex justify-center gap-5 overflow-hidden py-4 sm:gap-8">
        @foreach (\App\Models\HomeImage::limit(5)->get() as $image)
            <div
                class="relative aspect-9/10 w-44 flex-none overflow-hidden rounded-xl bg-zinc-100 sm:w-72 sm:rounded-2xl dark:bg-zinc-800 {{ $loop->odd ? 'rotate-2' : '-rotate-2' }}">
                <img alt="{{$image->id}}" loading="lazy"
                     class="absolute inset-0 h-full w-full object-cover" style="color:transparent"
                     src="{{ $image->url }}">
            </div>
        @endforeach
    </div>
</div>
