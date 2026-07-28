<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiProcessorService
{
    protected string $geminiApiKey;
    protected string $openaiApiKey;

    public function __construct()
    {
        $this->geminiApiKey = env('GEMINI_API_KEY', '');
        $this->openaiApiKey = env('OPENAI_API_KEY', '');
    }

    /**
     * Process transcript & description to extract structural mod pack data
     */
    public function processVideoData(string $title, string $description, string $transcript): ?array
    {
        $prompt = $this->buildPrompt($title, $description, $transcript);
        $extracted = null;

        if (!empty($this->geminiApiKey)) {
            $res = $this->callGemini($prompt);
            if ($res) {
                $extracted = $res;
            }
        }

        if (!$extracted && !empty($this->openaiApiKey)) {
            $res = $this->callOpenAi($prompt);
            if ($res) {
                $extracted = $res;
            }
        }

        if (!$extracted) {
            Log::warning('AI API request failed or returned invalid data. Falling back to local rules-based extractor.');
            $extracted = $this->localFallbackExtractor($title, $description, $transcript);
        }

        // Clean and sanitize the extracted mods list before returning
        if (isset($extracted['mods']) && is_array($extracted['mods'])) {
            $extracted['mods'] = $this->cleanModsList($extracted['mods']);
        }

        return $extracted;
    }

    /**
     * Sanitize and filter extracted mods to remove boilerplate text and duplicates
     */
    protected function cleanModsList(array $mods): array
    {
        $cleaned = [];
        $order = 1;
        $seen = [];

        $promotionalBlacklist = [
            'support me', 'support the', 'use this link', 'use my link', 'subscribe', 'discord', 'patreon', 'twitter', 
            'instagram', 'facebook', 'paypal', 'donation', 'donate', 'my specs', 'pc specs', 'social media', 
            'merch', 'shop', 'buy here', 'buy the game', 'follow me', 'my website', 'join my', 'join the', 
            'playlist', 'watch next', 'sponsored', 'sponsor', 'click here', 'download here', 'check out', 
            'get it here', 'link:', 'links:', 'music by', 'credits:', 'credit:', 'special thanks', 
            'support channel', 'business inquiry', 'business email', 'my setup', 'winrar', 'win-rar', '7zip', '7-zip',
            'nexus mods', 'nexusmods', 'mod manager', 'modmanager', 'vortex', 'mod organizer', 'mo2',
            'what you\'ll learn', 'what you will learn', 'how to install', 'how to download', 'tutorial',
            'tiktok', 'twitch', 'mod list', 'modlist', 'mod-list', 'youtube', 'github.com', 'reddit'
        ];

        foreach ($mods as $mod) {
            if (empty($mod['name'])) {
                continue;
            }

            $name = trim(strip_tags($mod['name']));

            // Filter out separators
            if (preg_match('/[-=_*•|~]{3,}/', $name)) {
                continue;
            }

            // Filter out websites/URLs
            if (preg_match('/https?:\/\/\S+/i', $name) || preg_match('/\b(www|\.com|\.org|\.net|\.zip)\b/i', $name)) {
                continue;
            }

            // Filter out promotional text
            $isPromotional = false;
            $lowerName = strtolower($name);
            foreach ($promotionalBlacklist as $term) {
                if (str_contains($lowerName, $term)) {
                    $isPromotional = true;
                    break;
                }
            }

            if ($isPromotional) {
                continue;
            }

            // Deduplicate
            $normalized = strtolower(preg_replace('/[^a-z0-9]/', '', $name));
            if (isset($seen[$normalized])) {
                continue;
            }

            if (strlen($name) > 2 && strlen($name) < 60) {
                $seen[$normalized] = true;
                $mod['name'] = $name;
                $mod['load_order'] = $order++;
                $cleaned[] = $mod;
            }
        }

        return $cleaned;
    }

    protected function buildPrompt(string $title, string $description, string $transcript): string
    {
        return <<<PROMPT
You are a Senior Modding Expert. Analyze the following video data (Title, Description, and Transcript) of a video game mod load order.
Extract the structural information and return EXACTLY a valid JSON object matching the schema below. Do not include any markdown styling like ```json or any other text before/after.

JSON Schema expected:
{
  "game_versions": ["string"] (array of strings, e.g. ["1.6.640"] or ["1.2.8", "1.2.9"] - deduce all compatible versions mentioned, default to ["unknown"] if not specified),
  "title_en": "string (create a high-performing clickbait SEO title in English)",
  "title_ar": "string (create an attractive clickbait SEO title in Arabic)",
  "description_en": "string (short english summary of the modpack and its goals)",
  "description_ar": "string (short arabic summary of the modpack and its goals)",
  "mods": [
    {
      "name": "string (clean name of the mod)",
      "load_order": integer (1-indexed incremental number representing its position in the list),
      "confidence": "string (high|low) (high if you are confident this is a real mod name, low if it is a generic tool, site, or you are unsure)",
      "source_snippet": "string (the exact sentence or text line from the Description or Transcript from which you extracted this mod name)",
      "nexus_url": "string or null (search your knowledge for a likely Nexus Mods link, e.g., https://www.nexusmods.com/skyrimspecialedition/mods/[id], or keep null)",
      "steam_url": "string or null (search your knowledge for a likely Steam Workshop link, e.g., https://steamcommunity.com/sharedfiles/filedetails/?id=[id], or keep null)",
      "download_url": "string or null (direct download url if different from nexus/steam)"
    }
  ]
}

Few-Shot Examples for "mods" extraction:
Example 1:
Description segment: "1. Skyrim Flora Overhaul - this completely retextures the trees: https://www.nexusmods.com/skyrimspecialedition/mods/2154"
Extracted JSON Mod:
{
  "name": "Skyrim Flora Overhaul",
  "load_order": 1,
  "confidence": "high",
  "source_snippet": "1. Skyrim Flora Overhaul - this completely retextures the trees",
  "nexus_url": "https://www.nexusmods.com/skyrimspecialedition/mods/2154",
  "steam_url": null,
  "download_url": "https://www.nexusmods.com/skyrimspecialedition/mods/2154"
}

Example 2:
Description segment: "Also grab the Address Library if you run SKSE plugins."
Extracted JSON Mod:
{
  "name": "Address Library for SKSE Plugins",
  "load_order": 2,
  "confidence": "high",
  "source_snippet": "Also grab the Address Library if you run SKSE plugins.",
  "nexus_url": null,
  "steam_url": null,
  "download_url": null
}

Video Title: $title
Video Description: $description
Transcript: $transcript
PROMPT;
    }

    protected function callWithRetry(callable $callback, int $maxRetries = 3, int $initialDelayMs = 500)
    {
        $retries = 0;
        while (true) {
            try {
                $result = $callback();
                if ($result !== null) {
                    return $result;
                }
            } catch (\Exception $e) {
                Log::warning("API call failed on attempt " . ($retries + 1) . ": " . $e->getMessage());
            }

            $retries++;
            if ($retries >= $maxRetries) {
                break;
            }

            // Exponential backoff
            $delay = $initialDelayMs * pow(2, $retries - 1);
            usleep($delay * 1000);
        }
        return null;
    }

    protected function callGemini(string $prompt): ?array
    {
        return $this->callWithRetry(function() use ($prompt) {
            $response = Http::timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$this->geminiApiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $text = trim($text);
                if (str_starts_with($text, '```json')) {
                    $text = substr($text, 7);
                    if (str_ends_with($text, '```')) {
                        $text = substr($text, 0, -3);
                    }
                }
                $decoded = json_decode(trim($text), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }

            Log::error('Gemini API call failed or returned invalid JSON: ' . $response->body());
            return null;
        });
    }

    protected function callOpenAi(string $prompt): ?array
    {
        return $this->callWithRetry(function() use ($prompt) {
            $response = Http::timeout(10)->withToken($this->openaiApiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'response_format' => ['type' => 'json_object'],
                ]);

            if ($response->successful()) {
                $text = $response->json()['choices'][0]['message']['content'] ?? '';
                $decoded = json_decode($text, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }

            Log::error('OpenAI API call failed or returned invalid JSON: ' . $response->body());
            return null;
        });
    }

    /**
     * Local rules-based extractor that uses regular expressions and text scanning
     * to populate schema if no AI keys are available.
     */
    protected function localFallbackExtractor(string $title, string $description, string $transcript): array
    {
        // 1. Deducing game versions using regex
        $versions = [];
        if (preg_match_all('/\b\d+\.\d+\.\d+\b/', $title . ' ' . $description, $matches)) {
            $versions = array_unique($matches[0]);
        } elseif (preg_match_all('/\b\d+\.\d+\b/', $title . ' ' . $description, $matches)) {
            $versions = array_unique($matches[0]);
        }

        if (empty($versions)) {
            $versions = ['unknown'];
        }

        $mainVersion = $versions[0];

        // 2. Clickbait titles
        $gameName = 'Game';
        if (str_contains(strtolower($title), 'skyrim')) {
            $gameName = 'Skyrim';
            $titleEn = 'Ultimate Skyrim Graphics & Combat Overhaul for v' . $mainVersion;
            $titleAr = 'تجميعة مودات سكاي ريم الأسطورية للرسومات والقتال للتحديث ' . $mainVersion;
        } elseif (str_contains(strtolower($title), 'bannerlord')) {
            $gameName = 'Bannerlord';
            $titleEn = 'Bannerlord Realism Modlist & Battle Tactics v' . $mainVersion;
            $titleAr = 'ترتيب مودات واقعية المعارك والرسومات للعبة بانرلورد ' . $mainVersion;
        } else {
            $titleEn = 'New Action Mod Pack Load Order Guide - ' . $mainVersion;
            $titleAr = 'تجميعة المودات الجديدة والكاملة للتحديث ' . $mainVersion;
        }

        // 3. Simple mod parsing from description and transcript
        $mods = [];
        $lines = explode("\n", $description . "\n" . $transcript);
        $order = 1;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $modName = null;
            $extractedUrl = null;

            // Pass 1: Standard bullets or numbered lists
            if (preg_match('/^\d+[\.\)\-]\s*(.+)$/', $line, $matches)) {
                $modName = trim($matches[1]);
            } elseif (preg_match('/^[-*•]\s*(.+)$/', $line, $matches)) {
                $modName = trim($matches[1]);
            } else {
                // Pass 2: Detect lines that look like "Mod Name: https://url"
                if (preg_match('/(https?:\/\/\S+)/i', $line, $urlMatches)) {
                    $extractedUrl = $urlMatches[1];
                    $cleanLine = trim(str_replace($extractedUrl, '', $line));
                    // Remove colons/dashes/brackets/parentheses at start and end
                    $cleanLine = preg_replace('/[:\-\|=(\[\]\s]+$/', '', $cleanLine);
                    $cleanLine = preg_replace('/^[:\-\|=(\[\]\s]+/', '', $cleanLine);
                    $cleanLine = trim($cleanLine);
                    
                    if (strlen($cleanLine) > 2 && strlen($cleanLine) < 60) {
                        $modName = $cleanLine;
                    }
                }
            }

            if ($modName) {
                // Extract URL if not already done in Pass 2
                if (!$extractedUrl && preg_match('/(https?:\/\/\S+)/i', $modName, $urlMatches)) {
                    $extractedUrl = $urlMatches[1];
                    $modName = trim(str_replace($extractedUrl, '', $modName));
                }

                // Clean name punctuation
                $modName = preg_replace('/[:\-\|=(\[\]\s\/\*\+]+$/', '', $modName);
                $modName = preg_replace('/^[:\-\|=(\[\]\s\/\*\+]+/', '', $modName);
                $modName = trim(strip_tags($modName));

                // Blacklist of typical YouTube description promotional/boiler-plate text
                $promotionalBlacklist = [
                    'support me', 'support the', 'use this link', 'use my link', 'subscribe', 'discord', 'patreon', 'twitter', 
                    'instagram', 'facebook', 'paypal', 'donation', 'donate', 'my specs', 'pc specs', 'social media', 
                    'merch', 'shop', 'buy here', 'buy the game', 'follow me', 'my website', 'join my', 'join the', 
                    'playlist', 'watch next', 'sponsored', 'sponsor', 'click here', 'download here', 'check out', 
                    'get it here', 'link:', 'links:', 'music by', 'credits:', 'credit:', 'special thanks', 
                    'support channel', 'business inquiry', 'business email', 'my setup', 'winrar', 'win-rar', '7zip', '7-zip',
                    'nexus mods', 'nexusmods', 'mod manager', 'modmanager', 'vortex', 'mod organizer', 'mo2',
                    'what you\'ll learn', 'what you will learn', 'how to install', 'how to download', 'tutorial',
                    'tiktok', 'twitch', 'mod list', 'modlist', 'mod-list', 'youtube', 'github.com', 'reddit'
                ];

                $isPromotional = false;
                $lowerModName = strtolower($modName);
                foreach ($promotionalBlacklist as $term) {
                    if (str_contains($lowerModName, $term)) {
                        $isPromotional = true;
                        break;
                    }
                }

                if ($isPromotional) {
                    continue;
                }

                if (strlen($modName) > 2 && strlen($modName) < 60) {
                    $hasSymbols = preg_match('/[?!\*\+]/', $modName);
                    $confidence = (strlen($modName) < 4 || $hasSymbols) ? 'low' : 'high';
                    $mods[] = [
                        'name' => $modName,
                        'load_order' => $order++,
                        'confidence' => $confidence,
                        'source_snippet' => $line,
                        'nexus_url' => str_contains($extractedUrl, 'nexusmods.com') ? $extractedUrl : null,
                        'steam_url' => str_contains($extractedUrl, 'steamcommunity.com') ? $extractedUrl : null,
                        'download_url' => $extractedUrl,
                    ];
                }
            }
        }

        // If no mods were extracted from description, return empty array
        if (empty($mods)) {
            $mods = [];
        }

        return [
            'game_version' => $mainVersion,
            'game_versions' => $versions,
            'title_en' => $titleEn,
            'title_ar' => $titleAr,
            'description_en' => 'Automated mod pack extracted from YouTube video.',
            'description_ar' => 'تجميعة مودات مستخرجة تلقائياً من فيديو يوتيوب.',
            'mods' => $mods,
        ];
    }
}
