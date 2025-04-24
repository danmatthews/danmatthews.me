@props([
    'url' => '',
    'title' => '',
])
<a class="relative text-center text-sm font-medium block {{ request()->path() == $url ? 'underline' : ''  }}"
   data-active=""
   href="{{ url($url) }}">{{ $title }}</a>
