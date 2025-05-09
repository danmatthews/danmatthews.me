@props([
    'url' => '',
    'title' => '',
    'active' => request()->path() == $url ? 'underline' : ''
])
<a class="relative text-center text-sm text-black dark:text-white font-medium block {{ $active ? 'underline' : ''  }}"
   data-active=""
   href="{{ url($url) }}">{{ $title }}</a>
