<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Download an image from URL and save it to public storage.
     * Returns the relative public path (e.g., storage/mods/uuid.jpg).
     */
    public static function downloadAndSaveImage(string $url, string $folder = 'mods'): ?string
    {
        try {
            // First, check if the URL is from our own domain to avoid loop downloading
            if (str_contains($url, request()->getHost())) {
                return null;
            }

            // Nexus uses strict hotlinking rules; pass a standard User-Agent
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
            ])->timeout(10)->get($url);

            if ($response->successful()) {
                $imageContent = $response->body();
                
                // Determine extension from URL or fallback to jpg
                $extension = 'jpg';
                $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
                if (isset($pathInfo['extension'])) {
                    $extension = explode('?', $pathInfo['extension'])[0];
                }

                $filename = Str::uuid() . '.' . $extension;
                $relativePath = $folder . '/' . $filename;

                // Save to public storage disk (storage/app/public/mods/...)
                Storage::disk('public')->put($relativePath, $imageContent);

                // Return the public URL accessible path
                return 'storage/' . $relativePath;
            }
        } catch (\Exception $e) {
            Log::error("Failed to download image from {$url}: " . $e->getMessage());
        }

        return null;
    }
}
