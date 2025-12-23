  <meta name="description" content="{{ $postMeta['excerpt'] }}">
  <meta property="og:site_name" content="{{ $postMeta['site_name'] }}">
  <meta property="og:type" content="article">
  <meta property="og:title" content="{{ $postMeta['title'] }}">
  <meta property="og:description" content="{{ $postMeta['excerpt'] }}">
  <meta property="og:image" content="{{ $postMeta['og_image'] }}">
  <meta name="twitter:title" content=" {{ $postMeta['title'] }}">
  <meta name="twitter:description" content="{{ $postMeta['excerpt'] }}">
  <meta name="twitter:image" content="{{ $postMeta['og_image'] }}">
  <link rel="canonical" href="{{ $postMeta['url'] }}">
  <script type="application/ld+json">{{ $postMeta['structured_data'] }}</script>
