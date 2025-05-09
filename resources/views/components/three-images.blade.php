@php
    $images = collect([
        asset('images/home_images/1.jpg'),
        asset('images/home_images/2.jpg'),
        asset('images/home_images/3.jpg'),
        asset('images/home_images/4.jpg'),
        asset('images/home_images/5.jpg'),
    ])->shuffle()->take(3);

@endphp
<div>
<div class="inline-flex items-center  group hover:gap-3 mb-8">
     
    @foreach ($images as $image) 
    <img class="aspect-square w-24 rounded-sm -ml-3 first:ml-0 group-hover:ml-3 -rotate-3 group-hover:rotate-0 transition-all" src="{{ $image }}" />
    @endforeach

</div>
</div>