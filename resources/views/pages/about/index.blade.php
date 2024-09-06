<x-layouts.app>

    <div class="sm:px-8 mt-16 sm:mt-32">
        <div class="mx-auto w-full max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <div class="grid grid-cols-1 gap-y-16 lg:grid-cols-2 lg:grid-rows-[auto_1fr] lg:gap-y-12">
                        <div class="lg:pl-20">
                            <div class="max-w-xs px-2.5 lg:max-w-none"><img alt="" loading="lazy" width="800"
                                    height="800" decoding="async" data-nimg="1"
                                    class="aspect-square rounded-sm bg-zinc-100 object-cover dark:bg-zinc-800"
                                    sizes="(min-width: 1024px) 32rem, 20rem" src="{{ asset('images/me.png') }}"
                                    style="color: transparent;"></div>
                        </div>
                        <div class="lg:order-first lg:row-span-2">
                            <h1 class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100">
                                I’m Dan, and I write full stack applications with Laravel.</h1>
                            <div class="mt-6 space-y-7 text-base text-zinc-600 dark:text-zinc-400 prose">
                                {!! markdown(config('site.about')) !!}
                            </div>
                        </div>
                        <div class="lg:pl-20">
                            <ul role="list">
                                <li class="flex"><a
                                        class="group flex text-sm font-medium text-zinc-800 transition hover:text-orange dark:text-zinc-200 dark:hover:text-orange"
                                        href="{{ config('site.social-links.twitter') }}">
                                        <svg class="w-6 fill-current" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M14 3h-4v2H8v4h2v2h1v8H6v-4h2v-2H4v6h2v2h12v-2h2v-6h-4v2h2v4h-5v-8h1V9h2V5h-2V3zm0 2v4h-4V5h4z"
                                                fill="currentColor" />
                                        </svg>
                                        <span class="ml-4">Follow on Twitter<super>*</super></span></a></li>
                                <li class="mt-4 flex"><a
                                        class="group flex text-sm font-medium text-zinc-800 transition hover:text-orange dark:text-zinc-200 dark:hover:text-orange"
                                        href="{{ config('site.social-links.instagram') }}">
                                        <svg class="w-6 fill-current" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M14 3h-4v2H8v4h2v2h1v8H6v-4h2v-2H4v6h2v2h12v-2h2v-6h-4v2h2v4h-5v-8h1V9h2V5h-2V3zm0 2v4h-4V5h4z"
                                                fill="currentColor" />
                                        </svg>
                                        <span class="ml-4">Follow on Instagram</span></a></li>
                                <li class="mt-4 flex"><a
                                        class="group flex text-sm font-medium text-zinc-800 transition hover:text-orange dark:text-zinc-200 dark:hover:text-orange"
                                        href="{{ config('site.social-links.github') }}">
                                        <svg class="w-6 fill-current" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M14 3h-4v2H8v4h2v2h1v8H6v-4h2v-2H4v6h2v2h12v-2h2v-6h-4v2h2v4h-5v-8h1V9h2V5h-2V3zm0 2v4h-4V5h4z"
                                                fill="currentColor" />
                                        </svg>
                                        <span class="ml-4">Follow on GitHub</span></a></li>
                                <li class="mt-4 flex"><a
                                        class="group flex text-sm font-medium text-zinc-800 transition hover:text-orange dark:text-zinc-200 dark:hover:text-orange"
                                        href="{{ config('site.social-links.linkedin') }}">
                                        <svg class="w-6 fill-current" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M14 3h-4v2H8v4h2v2h1v8H6v-4h2v-2H4v6h2v2h12v-2h2v-6h-4v2h2v4h-5v-8h1V9h2V5h-2V3zm0 2v4h-4V5h4z"
                                                fill="currentColor" />
                                        </svg>
                                        <span class="ml-4">Follow on LinkedIn</span></a></li>


                                <li class="mt-4  flex"><a
                                        class="group flex text-sm font-medium text-zinc-800 transition hover:text-orange dark:text-zinc-200 dark:hover:text-orange"
                                        href="mailto:dan@danmatthews.me">
                                        <svg class="fill-current w-5" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M22 4H2v16h20V4zM4 18V6h16v12H4zM8 8H6v2h2v2h2v2h4v-2h2v-2h2V8h-2v2h-2v2h-4v-2H8V8z"
                                                fill="currentColor" />
                                        </svg>
                                        <span class="ml-4">dan@danmatthews.me</span></a></li>
                                <li class="mt-8">
                                    <p class="text-zinc-400 text-xs">
                                        <super>*</super>I will never call it 'X'
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
