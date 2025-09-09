<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach($urls as $url)
    <url>
        <loc>{{ $url['url'] }}</loc>
        @if(isset($url['lastmod']))
        <lastmod>{{ \Carbon\Carbon::parse($url['lastmod'])->toIso8601String() }}</lastmod>
        @endif
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        <priority>{{ $url['priority'] }}</priority>
    </url>
    @endforeach
</urlset>