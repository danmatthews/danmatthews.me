@php

    @endphp
<div class="sm:px-8 mt-9">


    <div class="mx-auto w-full max-w-7xl lg:px-8  bg-green-100">
        <div class="relative px-4 sm:px-8 lg:px-12 bg-red-100">
            <div class="mx-auto bg-yellow-200 max-w-2xl lg:max-w-5xl flex gap-6">

                <div class="max-w-2xl bg-blue-200">
                    <h1 class=" font-extrabold  sm:text-4xl">
                        <span
                            class="inline-block tracking-tighter pr-1  ">{!! markdown(config('site.home.headline')) !!}</span>
                    </h1>
                    <div class="mt-6 text-base has-links">
                        {!! markdown(config('site.home.subheader')) !!}</div>
                    <div class="hidden mt-6 flex gap-6"><a class="group -m-1 p-1" aria-label="Follow on X"
                                                           target="_blank"
                                                           href="{{ config('site.social-links.twitter') }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"
                                 class="h-6 w-6 fill-zinc-500 transition group-hover:fill-zinc-600  ">
                                <path
                                    d="M13.3174 10.7749L19.1457 4H17.7646L12.7039 9.88256L8.66193 4H4L10.1122 12.8955L4 20H5.38119L10.7254 13.7878L14.994 20H19.656L13.3171 10.7749H13.3174ZM11.4257 12.9738L10.8064 12.0881L5.87886 5.03974H8.00029L11.9769 10.728L12.5962 11.6137L17.7652 19.0075H15.6438L11.4257 12.9742V12.9738Z">
                                </path>
                            </svg>
                        </a><a class="group -m-1 p-1" aria-label="Follow on Instagram" target="_blank"
                               href="{{ config('site.social-links.instagram') }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"
                                 class="h-6 w-6 fill-zinc-500 transition group-hover:fill-zinc-600 ">
                                <path
                                    d="M12 3c-2.444 0-2.75.01-3.71.054-.959.044-1.613.196-2.185.418A4.412 4.412 0 0 0 4.51 4.511c-.5.5-.809 1.002-1.039 1.594-.222.572-.374 1.226-.418 2.184C3.01 9.25 3 9.556 3 12s.01 2.75.054 3.71c.044.959.196 1.613.418 2.185.23.592.538 1.094 1.039 1.595.5.5 1.002.808 1.594 1.038.572.222 1.226.374 2.184.418C9.25 20.99 9.556 21 12 21s2.75-.01 3.71-.054c.959-.044 1.613-.196 2.185-.419a4.412 4.412 0 0 0 1.595-1.038c.5-.5.808-1.002 1.038-1.594.222-.572.374-1.226.418-2.184.044-.96.054-1.267.054-3.711s-.01-2.75-.054-3.71c-.044-.959-.196-1.613-.419-2.185A4.412 4.412 0 0 0 19.49 4.51c-.5-.5-1.002-.809-1.594-1.039-.572-.222-1.226-.374-2.184-.418C14.75 3.01 14.444 3 12 3Zm0 1.622c2.403 0 2.688.009 3.637.052.877.04 1.354.187 1.67.31.421.163.72.358 1.036.673.315.315.51.615.673 1.035.123.317.27.794.31 1.671.043.95.052 1.234.052 3.637s-.009 2.688-.052 3.637c-.04.877-.187 1.354-.31 1.67-.163.421-.358.72-.673 1.036a2.79 2.79 0 0 1-1.035.673c-.317.123-.794.27-1.671.31-.95.043-1.234.052-3.637.052s-2.688-.009-3.637-.052c-.877-.04-1.354-.187-1.67-.31a2.789 2.789 0 0 1-1.036-.673 2.79 2.79 0 0 1-.673-1.035c-.123-.317-.27-.794-.31-1.671-.043-.95-.052-1.234-.052-3.637s.009-2.688.052-3.637c.04-.877.187-1.354.31-1.67.163-.421.358-.72.673-1.036.315-.315.615-.51 1.035-.673.317-.123.794-.27 1.671-.31.95-.043 1.234-.052 3.637-.052Z">
                                </path>
                                <path
                                    d="M12 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm0-7.622a4.622 4.622 0 1 0 0 9.244 4.622 4.622 0 0 0 0-9.244Zm5.884-.182a1.08 1.08 0 1 1-2.16 0 1.08 1.08 0 0 1 2.16 0Z">
                                </path>
                            </svg>
                        </a><a class="group -m-1 p-1" aria-label="Follow on GitHub" target="_blank"
                               href="{{ config('site.social-links.github') }}">
                            <svg width="24" height="24" viewBox="0 0 24 24"
                                 class="fill-current stroke-[#FFA132]/50 h-6 w-6 fill-[#FFA132]"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 2H18V4H6V2Z" fill="current"/>
                                <path d="M10 14H8V12H10V14Z" fill="current"/>
                                <path d="M14 14V16H10V14H14Z" fill="current"/>
                                <path d="M14 14V12H16V14H14Z" fill="current"/>
                                <path
                                    d="M6 6V4H4V6H2V18H4V20H6V22H18V20H20V18H22V6H20V4H18V6H16V8H8V6H6ZM8 12V10H16V12H18V6H20V18H18V20H16V16H14V20H10V16H6V18H8V20H6V18H4V16H6V14H4V6H6V12H8Z"
                                    fill="current"/>
                            </svg>

                            {{-- <svg viewBox="0 0 24 24" aria-hidden="true"
                                class="h-6 w-6 fill-zinc-500 transition group-hover:fill-zinc-600 dark:fill-zinc-400 dark:group-hover:fill-zinc-300">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 2C6.475 2 2 6.588 2 12.253c0 4.537 2.862 8.369 6.838 9.727.5.09.687-.218.687-.487 0-.243-.013-1.05-.013-1.91C7 20.059 6.35 18.957 6.15 18.38c-.113-.295-.6-1.205-1.025-1.448-.35-.192-.85-.667-.013-.68.788-.012 1.35.744 1.538 1.051.9 1.551 2.338 1.116 2.912.846.088-.666.35-1.115.638-1.371-2.225-.256-4.55-1.14-4.55-5.062 0-1.115.387-2.038 1.025-2.756-.1-.256-.45-1.307.1-2.717 0 0 .837-.269 2.75 1.051.8-.23 1.65-.346 2.5-.346.85 0 1.7.115 2.5.346 1.912-1.333 2.75-1.05 2.75-1.05.55 1.409.2 2.46.1 2.716.637.718 1.025 1.628 1.025 2.756 0 3.934-2.337 4.806-4.562 5.062.362.32.675.936.675 1.897 0 1.371-.013 2.473-.013 2.82 0 .268.188.589.688.486a10.039 10.039 0 0 0 4.932-3.74A10.447 10.447 0 0 0 22 12.253C22 6.588 17.525 2 12 2Z">
                                </path>
                            </svg> --}}
                        </a><a class="group -m-1 p-1" aria-label="Follow on LinkedIn" target="_blank"
                               href="{{ config('site.social-links.linkedin') }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"
                                 class="h-6 w-6 fill-zinc-500 transition group-hover:fill-zinc-600 dark:fill-zinc-400 dark:group-hover:fill-zinc-300">
                                <path
                                    d="M18.335 18.339H15.67v-4.177c0-.996-.02-2.278-1.39-2.278-1.389 0-1.601 1.084-1.601 2.205v4.25h-2.666V9.75h2.56v1.17h.035c.358-.674 1.228-1.387 2.528-1.387 2.7 0 3.2 1.778 3.2 4.091v4.715zM7.003 8.575a1.546 1.546 0 01-1.548-1.549 1.548 1.548 0 111.547 1.549zm1.336 9.764H5.666V9.75H8.34v8.589zM19.67 3H4.329C3.593 3 3 3.58 3 4.297v15.406C3 20.42 3.594 21 4.328 21h15.338C20.4 21 21 20.42 21 19.703V4.297C21 3.58 20.4 3 19.666 3h.003z">
                                </path>
                            </svg>
                        </a></div>
                </div>
            </div>
        </div>
    </div>
</div>
