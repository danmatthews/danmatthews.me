@php
    $resume = \App\Models\ResumeEntry::all();
@endphp
<x-layouts.app title="About">
    <x-page-title class="mb-16">About</x-page-title>

    <x-three-images />

    <div class="max-w-2xl">
        <div class="">

            <div class="col-span-2">
                <div>

                    <p class="">
                        Hey there, I’m Dan.</p>
                    <div
                        class="mt-6 space-y-7 text-base underline-links-subtle">
                        {!! markdown(config('site.about')) !!}
                    </div>

                </div>
                <h2 class="text-base mt-16 mb-6 font-bold">Work History</h2>
                <div class="space-y-3">
                    @foreach ($resume as $entry)
                        <div class="flex items-center justify-between
                ">
                            <div class="flex gap-4 items-center">
                                <p class=" font-medium">
                                    @if ($entry->url)
                                        <a href="{{ $entry->url }}" target="_blank">{{ $entry->companyName }}</a>
                                    @else
                                        {{ $entry->companyName }}
                                    @endif
                                </p>
                                <p class=" text-gray-500">{{ $entry->jobTitle }}</p>
                                <p class="text-xs text-gray-500">{{ $entry->start }} - {{ $entry->end }}</p>

                            </div>

                        </div>
                    @endforeach

                </div>

                <h2 class="text-base mt-16 mb-6 font-bold">Socials</h2>
                <ul role="list" class="space-y-3">
                    <li class="flex"><a
                            class="group flex items-center gap-2"
                            href="{{ config('site.social-links.bluesky') }}">


                            <span class="">Follow on Bluesky</span>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                 class="size-4 text-gray-400">
                                <path
                                    d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                                <path
                                    d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                            </svg>
                        </a></li>

                    <li class="flex"><a
                            class="group flex items-center gap-2"
                            href="{{ config('site.social-links.instagram') }}">


                            <span class="">Follow me on Instagram</span>


                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                 class="size-4 text-gray-400">
                                <path
                                    d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                                <path
                                    d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                            </svg>
                        </a>
                    </li>
                    <li class="flex"><a
                            class="group flex items-center gap-2"
                            href="{{ config('site.social-links.github') }}">


                            <span class="">Follow me on GitHub</span>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                 class="size-4 text-gray-400">
                                <path
                                    d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                                <path
                                    d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                            </svg>
                        </a></li>
                    <li class="flex"><a
                            class="group flex gap-2 items-center"
                            href="{{ config('site.social-links.linkedin') }}">


                            <span class="">Follow me on LinkedIn</span>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                 class="size-4 text-gray-400">
                                <path
                                    d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                                <path
                                    d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                            </svg>
                        </a></li>


                    <li class=" "><a
                            class="flex gap-2 items-center group"
                            href="mailto:dan@danmatthews.me">

                            <span class="">Email me</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                 class="size-4 text-gray-400">
                                <path
                                    d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z"/>
                                <path
                                    d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z"/>
                            </svg>

                        </a></li>

                </ul>
            </div>

            <div class="hidden">
                <div class="mb-8 text-gray-500">
                    <img alt="" loading="lazy" width="800"
                         height="800" decoding="async" data-nimg="1"
                         class="aspect-square bg-zinc-100 object-cover rounded dark:bg-zinc-800"
                         sizes="(min-width: 1024px) 32rem, 20rem"
                         src="{{ asset('images/about.jpeg') }}"
                         style="color: transparent;">
                </div>
            </div>
        </div>
    </div>


</x-layouts.app>
