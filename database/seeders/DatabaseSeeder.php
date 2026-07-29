<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\ModPack;
use App\Models\Mod;
use App\Models\Comment;
use App\Models\Rating;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with clean initial structure.
     */
    public function run(): void
    {
        // 1. Create Main Admin Account
        $admin = User::firstOrCreate(
            ['email' => 'moadnp@gmail.com'],
            [
                'name' => 'Moad Admin',
                'password' => bcrypt('moad1234'),
                'is_admin' => true,
            ]
        );
        $admin->update(['is_admin' => true]);

        // 2. Create Supported Games
        $skyrim = Game::firstOrCreate(
            ['slug' => 'skyrim-special-edition'],
            [
                'name' => 'Skyrim Special Edition',
                'description' => 'The legendary fantasy masterpiece from Bethesda Game Studios.',
                'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/489830/header.jpg',
            ]
        );

        $bannerlord = Game::firstOrCreate(
            ['slug' => 'mount-and-blade-ii-bannerlord'],
            [
                'name' => 'Mount & Blade II: Bannerlord',
                'description' => 'A strategy/action RPG that lets you lead armies and build empires.',
                'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/261550/header.jpg',
            ]
        );

        $cyberpunk = Game::firstOrCreate(
            ['slug' => 'cyberpunk-2077'],
            [
                'name' => 'Cyberpunk 2077',
                'description' => 'An open-world, action-adventure RPG set in the dark future of Night City.',
                'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1091500/header.jpg',
            ]
        );

        $witcher3 = Game::firstOrCreate(
            ['slug' => 'the-witcher-3-wild-hunt'],
            [
                'name' => 'The Witcher 3: Wild Hunt',
                'description' => 'Become Geralt of Rivia, a professional monster slayer in a vast open world.',
                'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/292030/header.jpg',
            ]
        );

        $fallout4 = Game::firstOrCreate(
            ['slug' => 'fallout-4'],
            [
                'name' => 'Fallout 4',
                'description' => 'Bethesda Game Studios open-world post-apocalyptic survival RPG.',
                'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/377160/header.jpg',
            ]
        );

        $starfield = Game::firstOrCreate(
            ['slug' => 'starfield'],
            [
                'name' => 'Starfield',
                'description' => 'The next-generation space exploration role-playing game.',
                'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1716740/header.jpg',
            ]
        );

        // 3. Create Official Game Versions
        $skyrim_v1 = GameVersion::firstOrCreate(['game_id' => $skyrim->id, 'version' => '1.5.97']);
        $skyrim_v2 = GameVersion::firstOrCreate(['game_id' => $skyrim->id, 'version' => '1.6.640']);

        $bannerlord_v1 = GameVersion::firstOrCreate(['game_id' => $bannerlord->id, 'version' => '1.2.0']);
        $bannerlord_v2 = GameVersion::firstOrCreate(['game_id' => $bannerlord->id, 'version' => '1.2.8']);
        $bannerlord_v3 = GameVersion::firstOrCreate(['game_id' => $bannerlord->id, 'version' => '1.2.9']);

        $cyberpunk_v1 = GameVersion::firstOrCreate(['game_id' => $cyberpunk->id, 'version' => '2.0']);
        $cyberpunk_v2 = GameVersion::firstOrCreate(['game_id' => $cyberpunk->id, 'version' => '2.1']);

        $witcher_v1 = GameVersion::firstOrCreate(['game_id' => $witcher3->id, 'version' => '4.04']);
        $fallout_v1 = GameVersion::firstOrCreate(['game_id' => $fallout4->id, 'version' => '1.10.163']);
        $starfield_v1 = GameVersion::firstOrCreate(['game_id' => $starfield->id, 'version' => '1.11.33']);

        // 4. Create Initial Official Mod Packs
        $pack1 = ModPack::firstOrCreate(
            ['title_en' => 'Skyrim AE Ultimate Graphics & Realism Load Order'],
            [
                'title_ar' => 'ترتيب مودات سكاي ريم للرسومات والواقعية',
                'description_en' => 'A curated load order focused on stunning graphics, atmospheric lighting, and high-performance meshes. Built for Skyrim Anniversary Edition.',
                'description_ar' => 'تجميعة مودات منسقة بعناية تركز على الرسومات المذهلة، الإضاءة الجوية، والمجسمات عالية الأداء. مصممة لنسخة الذكرى السنوية (Anniversary Edition).',
                'youtube_video_id' => 'dQw4w9WgXcQ',
                'youtube_thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'local_thumbnail_path' => null,
                'views_count' => 1450,
                'upvotes' => 88,
                'downvotes' => 2,
                'is_published' => true,
                'created_by' => $admin->id,
            ]
        );
        $pack1->gameVersions()->sync([$skyrim_v2->id]);

        $pack2 = ModPack::firstOrCreate(
            ['title_en' => 'Bannerlord 1.2.0 Hardcore Battle Realism Setup'],
            [
                'title_ar' => 'ترتيب مودات واقعية المعارك الصعبة للعبة بانرلورد 1.2.0',
                'description_en' => 'Enhance combat tactics, AI behavior, and troop command mechanics for Bannerlord 1.2.0.',
                'description_ar' => 'تحسين تكتيكات القتال، سلوك الذكاء الاصطناعي، وميكانيكيات قيادة القوات للعبة بانرلورد 1.2.0.',
                'youtube_video_id' => '8bT_pC4j5J8',
                'youtube_thumbnail_url' => 'https://img.youtube.com/vi/8bT_pC4j5J8/maxresdefault.jpg',
                'local_thumbnail_path' => null,
                'views_count' => 980,
                'upvotes' => 64,
                'downvotes' => 1,
                'is_published' => true,
                'created_by' => $admin->id,
            ]
        );
        $pack2->gameVersions()->sync([$bannerlord_v1->id, $bannerlord_v2->id]);

        $pack3 = ModPack::firstOrCreate(
            ['title_en' => 'Cyberpunk 2.1 Hyper-Real Overdrive Visuals Modlist'],
            [
                'title_ar' => 'ترتيب مودات الرسومات الفائقة والواقعية للعبة سايبربانك 2.1',
                'description_en' => 'Maximize RayTracing Overdrive performance and photorealism in Night City. Tested stable.',
                'description_ar' => 'تحسين أداء تتبع الأشعة الفائق (RayTracing Overdrive) والواقعية البصرية في نايت سيتي. تم اختباره وهو مستقر تماماً.',
                'youtube_video_id' => 'x48u_2zKqGk',
                'youtube_thumbnail_url' => 'https://img.youtube.com/vi/x48u_2zKqGk/maxresdefault.jpg',
                'local_thumbnail_path' => null,
                'views_count' => 2100,
                'upvotes' => 120,
                'downvotes' => 3,
                'is_published' => true,
                'created_by' => $admin->id,
            ]
        );
        $pack3->gameVersions()->sync([$cyberpunk_v2->id]);

        $pack4 = ModPack::firstOrCreate(
            ['title_en' => 'Witcher 3 Next-Gen Complete Visual & Combat Enhancement'],
            [
                'title_ar' => 'ترتيب مودات ذا ويتشر 3 للرسومات المطورة والقتال',
                'description_en' => 'Comprehensive visual overhaul and combat realism for Witcher 3 Next-Gen 4.04 update.',
                'description_ar' => 'تعديل شامل للرسومات والواقعية القتالية لتحديث الجيل الجديد 4.04 من لعبة ذا ويتشر 3.',
                'youtube_video_id' => 'x48u_2zKqGk',
                'youtube_thumbnail_url' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/292030/header.jpg',
                'local_thumbnail_path' => null,
                'views_count' => 840,
                'upvotes' => 52,
                'downvotes' => 0,
                'is_published' => true,
                'created_by' => $admin->id,
            ]
        );
        $pack4->gameVersions()->sync([$witcher_v1->id]);

        $pack5 = ModPack::firstOrCreate(
            ['title_en' => 'Fallout 4 Anarchy Wasteland & Survival Immersion'],
            [
                'title_ar' => 'ترتيب مودات فالأوت 4 للواقعية والبقاء في الأراضي البور',
                'description_en' => 'Immersive survival, enhanced graphics, and settlement expansion for Fallout 4.',
                'description_ar' => 'مودات بقاء واقعية وتحسينات رسومية وتوسيع المستوطنات للعبة فالأوت 4.',
                'youtube_video_id' => 'dQw4w9WgXcQ',
                'youtube_thumbnail_url' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/377160/header.jpg',
                'local_thumbnail_path' => null,
                'views_count' => 620,
                'upvotes' => 41,
                'downvotes' => 1,
                'is_published' => true,
                'created_by' => $admin->id,
            ]
        );
        $pack5->gameVersions()->sync([$fallout_v1->id]);

        // 5. Populate Official Mods
        $mods1 = [
            ['name' => 'Address Library for SKSE Plugins', 'load_order' => 1, 'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/32444'],
            ['name' => 'SkyUI', 'load_order' => 2, 'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/12604'],
            ['name' => 'Static Mesh Improvement Mod (SMIM)', 'load_order' => 3, 'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/659'],
            ['name' => 'Noble Skyrim HD-2K', 'load_order' => 4, 'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/2140'],
            ['name' => 'Skyrim Flora Overhaul', 'load_order' => 5, 'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/2154'],
            ['name' => 'Lux (Lighting Overhaul)', 'load_order' => 6, 'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/43158'],
            ['name' => 'Folkvangr - Grass and Landscape Overhaul', 'load_order' => 7, 'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/44899'],
            ['name' => 'DynDOLOD 3.0', 'load_order' => 8, 'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/68164'],
        ];
        foreach ($mods1 as $m) {
            Mod::firstOrCreate(['mod_pack_id' => $pack1->id, 'name' => $m['name']], array_merge($m, ['game_id' => $skyrim->id, 'downloads_count' => rand(100, 5000)]));
        }

        $mods2 = [
            ['name' => 'Harmony', 'load_order' => 1, 'nexus_url' => 'https://www.nexusmods.com/mountandblade2bannerlord/mods/2006'],
            ['name' => 'ButterLib', 'load_order' => 2, 'nexus_url' => 'https://www.nexusmods.com/mountandblade2bannerlord/mods/2018'],
            ['name' => 'UIExtenderEx', 'load_order' => 3, 'nexus_url' => 'https://www.nexusmods.com/mountandblade2bannerlord/mods/2102'],
            ['name' => 'Mod Configuration Menu', 'load_order' => 4, 'nexus_url' => 'https://www.nexusmods.com/mountandblade2bannerlord/mods/2055'],
            ['name' => 'Realistic Battle Mod (RBM) - Combat module', 'load_order' => 5, 'nexus_url' => 'https://www.nexusmods.com/mountandblade2bannerlord/mods/791'],
            ['name' => 'Realistic Battle Mod (RBM) - AI module', 'load_order' => 6, 'nexus_url' => 'https://www.nexusmods.com/mountandblade2bannerlord/mods/791'],
            ['name' => 'Diplomacy', 'load_order' => 7, 'nexus_url' => 'https://www.nexusmods.com/mountandblade2bannerlord/mods/832'],
        ];
        foreach ($mods2 as $m) {
            Mod::firstOrCreate(['mod_pack_id' => $pack2->id, 'name' => $m['name']], array_merge($m, ['game_id' => $bannerlord->id, 'downloads_count' => rand(100, 4000)]));
        }

        $mods3 = [
            ['name' => 'Cyber Engine Tweaks (CET)', 'load_order' => 1, 'nexus_url' => 'https://www.nexusmods.com/cyberpunk2077/mods/107'],
            ['name' => 'redscript', 'load_order' => 2, 'nexus_url' => 'https://www.nexusmods.com/cyberpunk2077/mods/1511'],
            ['name' => 'ArchiveXL', 'load_order' => 3, 'nexus_url' => 'https://www.nexusmods.com/cyberpunk2077/mods/4198'],
            ['name' => 'TweakXL', 'load_order' => 4, 'nexus_url' => 'https://www.nexusmods.com/cyberpunk2077/mods/4197'],
            ['name' => 'Nova LUT (Pure Photorealistic Colors)', 'load_order' => 5, 'nexus_url' => 'https://www.nexusmods.com/cyberpunk2077/mods/11622'],
            ['name' => 'Ultra Quality RayTracing Optimizations', 'load_order' => 6, 'nexus_url' => 'https://www.nexusmods.com/cyberpunk2077/mods/10490'],
        ];
        foreach ($mods3 as $m) {
            Mod::firstOrCreate(['mod_pack_id' => $pack3->id, 'name' => $m['name']], array_merge($m, ['game_id' => $cyberpunk->id, 'downloads_count' => rand(100, 6000)]));
        }

        $mods4 = [
            ['name' => 'The Witcher 3 HD Reworked Project', 'load_order' => 1, 'nexus_url' => 'https://www.nexusmods.com/witcher3/mods/1021'],
            ['name' => 'Brothers In Arms - Ultimate Bug Fixes', 'load_order' => 2, 'nexus_url' => 'https://www.nexusmods.com/witcher3/mods/5752'],
            ['name' => 'Friendly HUD', 'load_order' => 3, 'nexus_url' => 'https://www.nexusmods.com/witcher3/mods/365'],
            ['name' => 'Fast Travel From Anywhere', 'load_order' => 4, 'nexus_url' => 'https://www.nexusmods.com/witcher3/mods/355'],
        ];
        foreach ($mods4 as $m) {
            Mod::firstOrCreate(['mod_pack_id' => $pack4->id, 'name' => $m['name']], array_merge($m, ['game_id' => $witcher3->id, 'downloads_count' => rand(100, 3000)]));
        }

        $mods5 = [
            ['name' => 'Fallout 4 Script Extender (F4SE)', 'load_order' => 1, 'nexus_url' => 'https://www.nexusmods.com/fallout4/mods/42147'],
            ['name' => 'Sim Settlements 2', 'load_order' => 2, 'nexus_url' => 'https://www.nexusmods.com/fallout4/mods/47976'],
            ['name' => 'Vivid Fallout - All in One', 'load_order' => 3, 'nexus_url' => 'https://www.nexusmods.com/fallout4/mods/25714'],
            ['name' => 'True Storms - Wasteland Edition', 'load_order' => 4, 'nexus_url' => 'https://www.nexusmods.com/fallout4/mods/4472'],
        ];
        foreach ($mods5 as $m) {
            Mod::firstOrCreate(['mod_pack_id' => $pack5->id, 'name' => $m['name']], array_merge($m, ['game_id' => $fallout4->id, 'downloads_count' => rand(100, 3500)]));
        }

        // 6. Populate Default Ad Slots
        if (\Illuminate\Support\Facades\Schema::hasTable('ad_slots')) {
            \App\Models\AdSlot::firstOrCreate(
                ['name' => 'الصفحة الرئيسية - أعلى المبنى'],
                ['impressions' => 0, 'clicks' => 0, 'is_active' => true]
            );
            \App\Models\AdSlot::firstOrCreate(
                ['name' => 'مستكشف المودات - الشريط الجانبي'],
                ['impressions' => 0, 'clicks' => 0, 'is_active' => true]
            );
            \App\Models\AdSlot::firstOrCreate(
                ['name' => 'تفاصيل المود - أسفل الرابط'],
                ['impressions' => 0, 'clicks' => 0, 'is_active' => true]
            );
        }
    }
}
