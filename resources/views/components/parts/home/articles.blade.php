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
                                <div class="flex gap-3 items-center mb-2">
                                    <div class="h-4 w-2 bg-zinc-400"></div>
                                    <time class="z-10  flex items-center text-sm text-zinc-600 dark:text-zinc-400"
                                          datetime="{{ $post->date }}">
                                        {{ $post->date }}
                                    </time>
                                </div>
                                <h2 class="text-base font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">
                                    <div
                                        class="absolute -inset-x-4 -inset-y-6 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 sm:-inset-x-6 sm:rounded-2xl dark:bg-zinc-800/50">
                                    </div>
                                    <a href="{{ route('posts.show', ['blog_post' => $post]) }}"
                                       class=""><span
                                            class="absolute -inset-x-4 -inset-y-6 z-20 sm:-inset-x-6 sm:rounded-2xl"></span><span
                                            class="relative z-10 vt-article-title">{{ $post->title }}</span></a>
                                </h2>


                                <p class="relative z-10 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $post->excerpt }}
                                </p>
                                <div aria-hidden="true"
                                     class="relative z-10 mt-4 flex items-center text-sm font-medium text-orange">Read
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

                        <div class="rounded-xs border border-[#FFA132] p-6 dark:border-[#FFA132]/50 relative">


                            @include('components.parts.box-decorator-orange')
                            <h2 class="flex text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                <svg width="24" height="24" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg" class="fill-current text-zinc-600 h-6 w-6 ">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M8 3H16V7H22V21H2V7H8V3ZM10 7H14V5H10V7ZM4 9V19H20V9H4Z"/>
                                </svg>


                                <span class="ml-3">Work</span>
                            </h2>
                            <ol class="mt-6 space-y-4">
                                @foreach (\App\Models\ResumeEntry::all() as $job)
                                    <li class="flex gap-4">
                                        <div
                                            class="relative mt-1 size-10 rounded-xs shrink-0 bg-orange flex items-center justify-center">
                                            <svg class="fill-current text-[#151515] size-6" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M10 2h4v4h-4V2zM3 7h18v2h-6v13h-2v-6h-2v6H9V9H3V7z"
                                                      fill="currentColor"/>
                                            </svg>
                                        </div>
                                        <dl class="flex flex-auto flex-wrap gap-x-2">
                                            <dt class="sr-only">Company</dt>
                                            <dd
                                                class="w-full flex-none text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $job->companyName }}
                                            </dd>
                                            <dt class="sr-only">Role</dt>
                                            <dd class="text-xs text-zinc-500 dark:text-zinc-400">{{ $job->jobTitle }}
                                            </dd>
                                            <dt class="sr-only">Date</dt>
                                            <dd class="ml-auto text-xs text-zinc-400 dark:text-zinc-500"
                                                aria-label="2019 until Present">
                                                <time datetime="2019">{{ $job->start }}</time>
                                                <span aria-hidden="true">—</span>
                                                <time datetime="2024">{{ $job->end }}</time>
                                            </dd>
                                        </dl>
                                    </li>
                                @endforeach

                            </ol>
                            {{--                            <a --}}
                            {{--                                class="inline-flex items-center gap-2 justify-center rounded-md py-2 px-3 text-sm outline-offset-2 transition active:transition-none bg-zinc-50 font-medium text-zinc-900 hover:bg-zinc-100 active:bg-zinc-100 active:text-zinc-900/60 dark:bg-zinc-800/50 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-zinc-50 dark:active:bg-zinc-800/50 dark:active:text-zinc-50/70 group mt-6 w-full" --}}
                            {{--                                href="#">Download CV --}}
                            {{--                                <svg viewBox="0 0 16 16" fill="none" aria-hidden="true" --}}
                            {{--                                     class="h-4 w-4 stroke-zinc-400 transition group-active:stroke-zinc-600 dark:group-hover:stroke-zinc-50 dark:group-active:stroke-zinc-50"> --}}
                            {{--                                    <path d="M4.75 8.75 8 12.25m0 0 3.25-3.5M8 12.25v-8.5" stroke-width="1.5" --}}
                            {{--                                          stroke-linecap="round" stroke-linejoin="round"></path> --}}
                            {{--                                </svg> --}}
                            {{--                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
