@php
    $posts = App\Models\BlogPost::paginate(10);
@endphp
<x-layouts.app title="Blog">
    <div class="sm:px-8 mt-16 sm:mt-32">
        <div class="mx-auto w-full max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <header class="max-w-2xl">
                        <h1 class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100">
                            Posts</h1>
                        <p class="mt-6 text-base text-zinc-600 dark:text-zinc-400">{{ config('site.posts.subheading') }}
                        </p>
                    </header>
                    <div class="mt-16 sm:mt-20">
                        <div class="md:border-l md:border-zinc-100 md:pl-6 md:dark:border-zinc-700/40">
                            <div class="flex max-w-3xl flex-col space-y-16">
                                @foreach ($posts as $post)
                                    <article class="md:grid md:grid-cols-4 md:items-baseline">
                                        <div class="md:col-span-3 group relative flex flex-col items-start">
                                            <h2
                                                class="text-base font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">
                                                <div
                                                    class="absolute -inset-x-4 -inset-y-6 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 sm:-inset-x-6 sm:rounded-2xl dark:bg-zinc-800/50">
                                                </div>
                                                <a href="{{ route('posts.show', ['blog_post' => $post]) }}"><span
                                                        class="absolute -inset-x-4 -inset-y-6 z-20 sm:-inset-x-6 sm:rounded-2xl"></span><span
                                                        class="relative z-10">{{ $post->title }}</span></a>
                                            </h2>
                                            <time
                                                class="md:hidden relative z-10 order-first mb-3 flex items-center text-sm text-zinc-400 dark:text-zinc-500 pl-3.5"
                                                datetime="2022-09-05"><span
                                                    class="absolute inset-y-0 left-0 flex items-center"
                                                    aria-hidden="true"><span
                                                        class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>{{ $post->date }}
                                            </time>
                                            <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                                {{ $post->excerpt }}</p>
                                            <div aria-hidden="true"
                                                class="relative z-10 mt-4 flex items-center text-sm font-medium text-orange">
                                                Read article
                                                <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"
                                                    class="ml-1 h-4 w-4 stroke-current">
                                                    <path d="M6.75 5.75 9.25 8l-2.5 2.25" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="order-first mb-3 space-y-1">
                                        <p
                                            class=" hidden md:block relative z-10   flex items-center text-sm text-zinc-400 dark:text-zinc-500"
                                            datetime="2022-09-05">{{ explode(',', $post->date)[1] }}
                                        </p>
                                        <p
                                            class="mt-0 hidden md:block relative z-10  mb-3 flex items-center text-sm text-zinc-400 dark:text-zinc-500"
                                            datetime="2022-09-05">{{ trim(explode(',', $post->date)[0]) }}
                                        </p>
                                        </div>
                                    </article>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
