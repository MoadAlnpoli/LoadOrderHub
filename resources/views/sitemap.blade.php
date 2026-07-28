<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach ($games as $game)
        <url>
            <loc>{{ route('games.show', $game->slug) }}</loc>
            <lastmod>{{ $game->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach ($mods as $mod)
        @if($mod->slug)
        <url>
            <loc>{{ route('mods.show', $mod->slug) }}</loc>
            <lastmod>{{ $mod->updated_at ? $mod->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
        @endif
    @endforeach
    @foreach ($modPacks as $mp)
        <url>
            <loc>{{ route('modpacks.show', $mp->id) }}</loc>
            <lastmod>{{ $mp->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
