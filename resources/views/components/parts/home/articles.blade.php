@php
    $posts = App\Models\BlogPost::paginate(10);
@endphp
<div class="sm:px-8 mt-24 md:mt-28">
    <div class="mx-auto w-full max-w-7xl lg:px-8">
        <div class="relative px-4 sm:px-8 lg:px-12">
            <div class="mx-auto max-w-2xl lg:max-w-5xl">
                <div class="mx-auto grid max-w-xl grid-cols-1 gap-y-20 lg:max-w-none lg:grid-cols-2">
                    <div class="flex flex-col gap-16">
                        @foreach ($posts as $post)
                            <article class="group relative flex flex-col items-start">
                                <h2 class="text-base font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">
                                    <div
                                        class="absolute -inset-x-4 -inset-y-6 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 sm:-inset-x-6 sm:rounded-2xl dark:bg-zinc-800/50">
                                    </div>
                                    <a href="{{ route('posts.show', ['blog_post' => $post]) }}"><span
                                            class="absolute -inset-x-4 -inset-y-6 z-20 sm:-inset-x-6 sm:rounded-2xl"></span><span
                                            class="relative z-10">{{$post->title}}</span></a>
                                </h2>
                                <time
                                    class="relative z-10 order-first mb-3 flex items-center text-sm text-zinc-400 dark:text-zinc-500 pl-3.5"
                                    datetime="2022-09-05"><span class="absolute inset-y-0 left-0 flex items-center"
                                                                aria-hidden="true"><span
                                            class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span></span>{{$post->date}}
                                </time>
                                <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $post->excerpt }}
                                </p>
                                <div aria-hidden="true"
                                     class="relative z-10 mt-4 flex items-center text-sm font-medium text-teal-500">Read
                                    article
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"
                                         class="ml-1 h-4 w-4 stroke-current">
                                        <path d="M6.75 5.75 9.25 8l-2.5 2.25" stroke-width="1.5" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </article>

                        @endforeach
                    </div>
                    <div class="space-y-10 lg:pl-16 xl:pl-24">

                        <div class="rounded-2xl border border-zinc-100 p-6 dark:border-zinc-700/40">
                            <h2 class="flex text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                <svg
                                    viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true" class="h-6 w-6 flex-none">
                                    <path
                                        d="M2.75 9.75a3 3 0 0 1 3-3h12.5a3 3 0 0 1 3 3v8.5a3 3 0 0 1-3 3H5.75a3 3 0 0 1-3-3v-8.5Z"
                                        class="fill-zinc-100 stroke-zinc-400 dark:fill-zinc-100/10 dark:stroke-zinc-500">
                                    </path>
                                    <path
                                        d="M3 14.25h6.249c.484 0 .952-.002 1.316.319l.777.682a.996.996 0 0 0 1.316 0l.777-.682c.364-.32.832-.319 1.316-.319H21M8.75 6.5V4.75a2 2 0 0 1 2-2h2.5a2 2 0 0 1 2 2V6.5"
                                        class="stroke-zinc-400 dark:stroke-zinc-500"></path>
                                </svg>
                                <span class="ml-3">Work</span></h2>
                            <ol class="mt-6 space-y-4">
                                @foreach (\App\Models\ResumeEntry::all() as $job)
                                    <li class="flex gap-4">
                                        <div
                                            class="relative mt-1 flex h-10 w-10 flex-none items-center justify-center rounded-full shadow-md shadow-zinc-800/5 ring-1 ring-zinc-900/5 dark:border dark:border-zinc-700/50 dark:bg-zinc-800 dark:ring-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="lucide lucide-hammer text-gray-400">
                                                <path d="m15 12-8.373 8.373a1 1 0 1 1-3-3L12 9"/>
                                                <path d="m18 15 4-4"/>
                                                <path
                                                    d="m21.5 11.5-1.914-1.914A2 2 0 0 1 19 8.172V7l-2.26-2.26a6 6 0 0 0-4.202-1.756L9 2.96l.92.82A6.18 6.18 0 0 1 12 8.4V10l2 2h1.172a2 2 0 0 1 1.414.586L18.5 14.5"/>
                                            </svg>
                                        </div>
                                        <dl class="flex flex-auto flex-wrap gap-x-2">
                                            <dt class="sr-only">Company</dt>
                                            <dd
                                                class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{$job->companyName}}
                                            </dd>
                                            <dt class="sr-only">Role</dt>
                                            <dd class="text-xs text-zinc-500 dark:text-zinc-400">{{$job->jobTitle}}</dd>
                                            <dt class="sr-only">Date</dt>
                                            <dd class="ml-auto text-xs text-zinc-400 dark:text-zinc-500"
                                                aria-label="2019 until Present">
                                                <time datetime="2019">{{$job->start}}</time>
                                                <span
                                                    aria-hidden="true">—</span>
                                                <time datetime="2024">{{$job->end}}</time>
                                            </dd>
                                        </dl>
                                    </li>
                                @endforeach

                            </ol>
                            {{--                            <a--}}
                            {{--                                class="inline-flex items-center gap-2 justify-center rounded-md py-2 px-3 text-sm outline-offset-2 transition active:transition-none bg-zinc-50 font-medium text-zinc-900 hover:bg-zinc-100 active:bg-zinc-100 active:text-zinc-900/60 dark:bg-zinc-800/50 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-zinc-50 dark:active:bg-zinc-800/50 dark:active:text-zinc-50/70 group mt-6 w-full"--}}
                            {{--                                href="#">Download CV--}}
                            {{--                                <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"--}}
                            {{--                                     class="h-4 w-4 stroke-zinc-400 transition group-active:stroke-zinc-600 dark:group-hover:stroke-zinc-50 dark:group-active:stroke-zinc-50">--}}
                            {{--                                    <path d="M4.75 8.75 8 12.25m0 0 3.25-3.5M8 12.25v-8.5" stroke-width="1.5"--}}
                            {{--                                          stroke-linecap="round" stroke-linejoin="round"></path>--}}
                            {{--                                </svg>--}}
                            {{--                            </a>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
