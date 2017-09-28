<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
    <url>
        <loc>{{ env('APP_URL') }}</loc>
        <lastmod>{{ Carbon\Carbon::now()->format('Y-m-d\TH:i:sP') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach($data as $component )
        @foreach($component as $componentItem)
            @foreach($componentItem->createSitemap() as $item)
                <url>
                    <loc>{{ env('APP_URL') }}{{ $item->fullUrl }}</loc>
                    <lastmod>{{ Carbon\Carbon::parse($item->updated_at)->format('Y-m-d\TH:i:sP')  }}</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                </url>
            @endforeach
        @endforeach
    @endforeach
</urlset>