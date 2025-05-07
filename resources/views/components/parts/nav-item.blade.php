@props([
    'url' => '',
    'title' => '',
    'active' => request()->path() == $url ? 'underline' : ''
])
<a class="relative text-center text-sm font-medium block {{ $active ? 'underline' : ''  }}"
   data-active=""
   href="{{ url($url) }}">{{ $title }}</a>
