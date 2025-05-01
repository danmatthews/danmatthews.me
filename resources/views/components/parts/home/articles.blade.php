@php
    $posts = App\Models\BlogPost::paginate(10);
@endphp
<div class="gap-8 max-w-3xl divide-y">
    @foreach ($posts as $post)
        <article class="py-8 first:pt-0 group relative flex flex-col items-start">

            <h2 class="text-3xl font-extrabold leading-10">
                <a href="{{ route('posts.show', ['blog_post' => $post]) }}"
                   class="">{{ $post->title }}</a>
            </h2>


            <p class="relative z-10 mt-4 text-base ">
                {{ $post->excerpt }}
            </p>


            <time class="text-sm text-gray-500 mt-4"
                  datetime="{{ $post->date }}">
                {{ $post->date }}
            </time>


        </article>
    @endforeach
</div>

<div class="flex items-center justify-between w-full">
    {{ $posts->links() }}
</div>
