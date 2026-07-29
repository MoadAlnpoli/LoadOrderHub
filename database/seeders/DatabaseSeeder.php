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
        $skyrim = Game::create([
            'name' => 'Skyrim Special Edition',
            'slug' => 'skyrim-special-edition',
            'description' => 'The legendary fantasy masterpiece from Bethesda Game Studios.',
            'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/489830/header.jpg',
        ]);

        $bannerlord = Game::create([
            'name' => 'Mount & Blade II: Bannerlord',
            'slug' => 'mount-and-blade-ii-bannerlord',
            'description' => 'A strategy/action RPG that lets you lead armies and build empires.',
            'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/261550/header.jpg',
        ]);

        $cyberpunk = Game::create([
            'name' => 'Cyberpunk 2077',
            'slug' => 'cyberpunk-2077',
            'description' => 'An open-world, action-adventure RPG set in the dark future of Night City.',
            'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1091500/header.jpg',
        ]);

        // 3. Create Official Game Versions
        $skyrim_v1 = GameVersion::create(['game_id' => $skyrim->id, 'version' => '1.5.97']);
        $skyrim_v2 = GameVersion::create(['game_id' => $skyrim->id, 'version' => '1.6.640']);

        $bannerlord_v1 = GameVersion::create(['game_id' => $bannerlord->id, 'version' => '1.2.0']);
        $bannerlord_v2 = GameVersion::create(['game_id' => $bannerlord->id, 'version' => '1.2.8']);
        $bannerlord_v3 = GameVersion::create(['game_id' => $bannerlord->id, 'version' => '1.2.9']);

        $cyberpunk_v1 = GameVersion::create(['game_id' => $cyberpunk->id, 'version' => '2.0']);
        $cyberpunk_v2 = GameVersion::create(['game_id' => $cyberpunk->id, 'version' => '2.1']);

        // 4. Create Initial Official Mod Packs (Clean stats, 0 fake counts)
        $pack1 = ModPack::create([
            'title_en' => 'Skyrim AE Ultimate Graphics & Realism Load Order',
            'title_ar' => 'ترتيب مودات سكاي ريم للرسومات والواقعية',
            'description_en' => 'A curated load order focused on stunning graphics, atmospheric lighting, and high-performance meshes. Built for Skyrim Anniversary Edition.',
            'description_ar' => 'تجميعة مودات منسقة بعناية تركز على الرسومات المذهلة، الإضاءة الجوية، والمجسمات عالية الأداء. مصممة لنسخة الذكرى السنوية (Anniversary Edition).',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'youtube_thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
            'local_thumbnail_path' => null,
            'views_count' => 0,
            'upvotes' => 0,
            'downvotes' => 0,
            'is_published' => true,
            'created_by' => $admin->id,
        ]);
        $pack1->gameVersions()->sync([$skyrim_v2->id]);

        $pack2 = ModPack::create([
            'title_en' => 'Bannerlord 1.2.0 Hardcore Battle Realism Setup',
            'title_ar' => 'ترتيب مودات واقعية المعارك الصعبة للعبة بانرلورد 1.2.0',
            'description_en' => 'Enhance combat tactics, AI behavior, and troop command mechanics for Bannerlord 1.2.0.',
            'description_ar' => 'تحسين تكتيكات القتال، سلوك الذكاء الاصطناعي، وميكانيكيات قيادة القوات للعبة بانرلورد 1.2.0.',
            'youtube_video_id' => '8bT_pC4j5J8',
            'youtube_thumbnail_url' => 'https://img.youtube.com/vi/8bT_pC4j5J8/maxresdefault.jpg',
            'local_thumbnail_path' => null,
            'views_count' => 0,
            'upvotes' => 0,
            'downvotes' => 0,
            'is_published' => true,
            'created_by' => $admin->id,
        ]);
        $pack2->gameVersions()->sync([$bannerlord_v1->id, $bannerlord_v2->id]);

        $pack3 = ModPack::create([
            'title_en' => 'Cyberpunk 2.1 Hyper-Real Overdrive Visuals Modlist',
            'title_ar' => 'ترتيب مودات الرسومات الفائقة والواقعية للعبة سايبربانك 2.1',
            'description_en' => 'Maximize RayTracing Overdrive performance and photorealism in Night City. Tested stable.',
            'description_ar' => 'تحسين أداء تتبع الأشعة الفائق (RayTracing Overdrive) والواقعية البصرية في نايت سيتي. تم اختباره وهو مستقر تماماً.',
            'youtube_video_id' => 'x48u_2zKqGk',
            'youtube_thumbnail_url' => 'https://img.youtube.com/vi/x48u_2zKqGk/maxresdefault.jpg',
            'local_thumbnail_path' => null,
            'views_count' => 0,
            'upvotes' => 0,
            'downvotes' => 0,
            'is_published' => true,
            'created_by' => $admin->id,
        ]);
        $pack3->gameVersions()->sync([$cyberpunk_v2->id]);

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
            Mod::create(array_merge($m, ['mod_pack_id' => $pack1->id]));
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
            Mod::create(array_merge($m, ['mod_pack_id' => $pack2->id]));
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
            Mod::firstOrCreate(['mod_pack_id' => $pack3->id, 'name' => $m['name']], $m);
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
