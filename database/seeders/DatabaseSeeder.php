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

        // 2. Create 35 Popular Supported Games with Real Data & Steam Images
        $gamesData = [
            ['slug' => 'skyrim-special-edition', 'name' => 'Skyrim Special Edition', 'description' => 'The legendary fantasy masterpiece from Bethesda Game Studios.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/489830/header.jpg'],
            ['slug' => 'mount-and-blade-ii-bannerlord', 'name' => 'Mount & Blade II: Bannerlord', 'description' => 'A strategy/action RPG that lets you lead armies and build empires.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/261550/header.jpg'],
            ['slug' => 'cyberpunk-2077', 'name' => 'Cyberpunk 2077', 'description' => 'An open-world, action-adventure RPG set in the dark future of Night City.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1091500/header.jpg'],
            ['slug' => 'the-witcher-3-wild-hunt', 'name' => 'The Witcher 3: Wild Hunt', 'description' => 'Become Geralt of Rivia, a professional monster slayer in a vast open world.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/292030/header.jpg'],
            ['slug' => 'fallout-4', 'name' => 'Fallout 4', 'description' => 'Bethesda Game Studios open-world post-apocalyptic survival RPG.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/377160/header.jpg'],
            ['slug' => 'starfield', 'name' => 'Starfield', 'description' => 'The next-generation space exploration role-playing game.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1716740/header.jpg'],
            ['slug' => 'elden-ring', 'name' => 'Elden Ring', 'description' => 'The action RPG fantasy epic created by FromSoftware and George R. R. Martin.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1245620/header.jpg'],
            ['slug' => 'grand-theft-auto-v', 'name' => 'Grand Theft Auto V', 'description' => 'Explore the vast award-winning world of Los Santos and Blaine County.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/271590/header.jpg'],
            ['slug' => 'red-dead-redemption-2', 'name' => 'Red Dead Redemption 2', 'description' => 'Arthur Morgan and the Van der Linde gang are outlaws on the run across America.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1174180/header.jpg'],
            ['slug' => 'baldurs-gate-3', 'name' => 'Baldur\'s Gate 3', 'description' => 'Gather your party and return to the Forgotten Realms in a next-generation RPG.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1086940/header.jpg'],
            ['slug' => 'hogwarts-legacy', 'name' => 'Hogwarts Legacy', 'description' => 'Experience Hogwarts in the 1800s. Your character is a student holding the key to an ancient secret.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/990080/header.jpg'],
            ['slug' => 'monster-hunter-world', 'name' => 'Monster Hunter: World', 'description' => 'Welcome to a new world! In Monster Hunter: World, enjoy the ultimate hunting experience.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/582010/header.jpg'],
            ['slug' => 'stardew-valley', 'name' => 'Stardew Valley', 'description' => 'You\'ve inherited your grandfather\'s old farm plot in Stardew Valley.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/413150/header.jpg'],
            ['slug' => 'fallout-new-vegas', 'name' => 'Fallout: New Vegas', 'description' => 'Feel the heat in New Vegas! Enjoy the ultimate post-apocalyptic Vegas experience.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/22380/header.jpg'],
            ['slug' => 'rimworld', 'name' => 'RimWorld', 'description' => 'A sci-fi colony sim driven by an intelligent AI storyteller.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/294100/header.jpg'],
            ['slug' => 'kingdom-come-deliverance', 'name' => 'Kingdom Come: Deliverance', 'description' => 'Story-driven open-world RPG that immerses you in an epic adventure in Bohemia.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/379430/header.jpg'],
            ['slug' => 'valheim', 'name' => 'Valheim', 'description' => 'A brutal exploration and survival game for 1-10 players set in a procedurally generated purgatory.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/892970/header.jpg'],
            ['slug' => 'dark-souls-iii', 'name' => 'DARK SOULS III', 'description' => 'Dark Souls continues to push the boundaries with the latest, ambitious chapter in the critically-acclaimed series.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/374320/header.jpg'],
            ['slug' => 'dragons-dogma-2', 'name' => 'Dragon\'s Dogma 2', 'description' => 'Dragon\'s Dogma 2 is a single player, narrative driven action-RPG.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2054970/header.jpg'],
            ['slug' => 'star-wars-jedi-survivor', 'name' => 'STAR WARS Jedi: Survivor', 'description' => 'The story of Cal Kestis continues in Star Wars Jedi: Survivor.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1770590/header.jpg'],
            ['slug' => 'palworld', 'name' => 'Palworld', 'description' => 'Fight, farm, build and work alongside mysterious creatures called Pals in this multiplayer survival game.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1623730/header.jpg'],
            ['slug' => 'helldivers-2', 'name' => 'HELLDIVERS 2', 'description' => 'The Galaxy’s Last Line of Offence. Enlist in the Helldivers and join the fight for Freedom across a hostile galaxy.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/553850/header.jpg'],
            ['slug' => 'resident-evil-4-remake', 'name' => 'Resident Evil 4 Remake', 'description' => 'Survival is only the beginning. Leon S. Kennedy faces the horrors of a remote Spanish village.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2050650/header.jpg'],
            ['slug' => 'fallout-3', 'name' => 'Fallout 3: Game of the Year Edition', 'description' => 'Prepare for the future with Vault-Tec in the Capital Wasteland.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/22370/header.jpg'],
            ['slug' => 'mass-effect-legendary-edition', 'name' => 'Mass Effect Legendary Edition', 'description' => 'Relive the cinematic saga of Commander Shepard in the highly-acclaimed Mass Effect trilogy.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1328670/header.jpg'],
            ['slug' => 'resident-evil-village', 'name' => 'Resident Evil Village', 'description' => 'Experience survival horror like never before in the eighth major installment in the Resident Evil franchise.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1196590/header.jpg'],
            ['slug' => 'cities-skylines-ii', 'name' => 'Cities: Skylines II', 'description' => 'Raise a city from the ground up and transform it into a thriving metropolis in the most realistic city builder ever.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/949230/header.jpg'],
            ['slug' => 'manor-lords', 'name' => 'Manor Lords', 'description' => 'Manor Lords is a medieval strategy game featuring in-depth city building, tactical battles, and complex economic sims.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1363080/header.jpg'],
            ['slug' => 'armored-core-vi', 'name' => 'ARMORED CORE VI FIRES OF RUBICON', 'description' => 'Combine fast-paced 3D mech combat with deep customization in Armored Core VI.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1888160/header.jpg'],
            ['slug' => 'the-sims-4', 'name' => 'The Sims 4', 'description' => 'Unleash your imagination and create a unique world of Sims that’s an expression of you.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1222670/header.jpg'],
            ['slug' => 'civilization-vi', 'name' => 'Sid Meier’s Civilization VI', 'description' => 'Civilization VI offers new ways to interact with your world, expand your empire across the map, and advance your culture.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/289070/header.jpg'],
            ['slug' => 'subnautica', 'name' => 'Subnautica', 'description' => 'Descend into the depths of an alien underwater world filled with wonder and peril.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/264710/header.jpg'],
            ['slug' => 'terraria', 'name' => 'Terraria', 'description' => 'Dig, Fight, Explore, Build! Nothing is impossible in this action-packed adventure game.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/105600/header.jpg'],
            ['slug' => 'sekiro-shadows-die-twice', 'name' => 'Sekiro: Shadows Die Twice', 'description' => 'Carve your own clever path to vengeance in the critically acclaimed adventure from FromSoftware.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/814380/header.jpg'],
            ['slug' => 'persona-5-royal', 'name' => 'Persona 5 Royal', 'description' => 'Don the mask of Joker and join the Phantom Thieves of Hearts as they break the chains of modern society.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1687950/header.jpg'],
        ];

        $gameModels = [];
        foreach ($gamesData as $gData) {
            $gModel = Game::firstOrCreate(['slug' => $gData['slug']], $gData);
            $gameModels[] = $gModel;
            // 3 Game Versions per game
            GameVersion::firstOrCreate(['game_id' => $gModel->id, 'version' => '1.0.0']);
            GameVersion::firstOrCreate(['game_id' => $gModel->id, 'version' => '1.2.5']);
            GameVersion::firstOrCreate(['game_id' => $gModel->id, 'version' => '2.0.0']);
        }

        // 3. Create Featured Mod Packs (1 per game = 35 ModPacks)
        $modPackModels = [];
        foreach ($gameModels as $index => $gModel) {
            $pack = ModPack::firstOrCreate(
                ['title_en' => "{$gModel->name} Ultimate Overhaul Modpack #" . ($index + 1)],
                [
                    'title_ar' => "تجميعة المودات الكاملة والشاملة للعبة {$gModel->name}",
                    'description_en' => "Complete collection of visual, framework, and performance mods for {$gModel->name}.",
                    'description_ar' => "تجميعة شاملة من أفضل المودات المحسنة والأطر البرمجية والرسومات للعبة {$gModel->name}.",
                    'youtube_video_id' => 'dQw4w9WgXcQ',
                    'youtube_thumbnail_url' => $gModel->thumbnail,
                    'local_thumbnail_path' => null,
                    'views_count' => rand(800, 15000),
                    'upvotes' => rand(100, 1200),
                    'downvotes' => rand(0, 20),
                    'is_published' => true,
                    'created_by' => $admin->id,
                ]
            );
            $vIds = $gModel->versions()->pluck('id')->toArray();
            if (!empty($vIds)) {
                $pack->gameVersions()->sync($vIds);
            }
            $modPackModels[$gModel->id] = $pack;
        }

        // 4. Generate 100 Curated Mods per Game = 3,500 Mods Total
        $modPrefixes = [
            'Ultimate HD', 'Enhanced Visuals', 'HD Re-texture Pack', 'Script Extender Core', 'Performance Boost Pro', 
            '4K Photorealistic Textures', 'Realistic Weather & Lighting', 'Overhaul Framework API', 'Community Patch 2026', 
            'Immersive Audio Overhaul', 'Fast Travel & Map Utility', 'Custom Cyber UI Interface', 'Advanced Tactical AI', 
            'RayTracing Ultra Reshade', 'Next-Gen Combat System', 'Expanded Storage & Inventory', 'Dynamic Shadows Fix', 
            'Expanded Spells & Magic', 'Dynamic Camera FX', 'Hardcore Survival Realism', 'LOD Distance Generator', 
            'FPS Unlocked Engine', 'True Weather FX', 'Seamless Co-op Engine', 'Better Animation Overhaul', 'Clean UI Redesign'
        ];
        $modCategories = ['Graphics', 'Fixes', 'Gameplay', 'Audio', 'Interface', 'Framework', 'Combat', 'Quests', 'Weapons', 'Outfits'];

        foreach ($gameModels as $gModel) {
            $pack = $modPackModels[$gModel->id] ?? null;
            $gameVersionIds = $gModel->versions()->pluck('id')->toArray();

            for ($i = 1; $i <= 100; $i++) {
                $prefix = $modPrefixes[array_rand($modPrefixes)];
                $modName = "{$prefix} for {$gModel->name} Mod #{$i}";
                $category = $modCategories[array_rand($modCategories)];

                $mod = Mod::firstOrCreate(
                    [
                        'game_id' => $gModel->id,
                        'name'    => $modName,
                    ],
                    [
                        'mod_pack_id'      => $pack?->id,
                        'slug'             => Str::slug($modName) . '-' . rand(100, 9999),
                        'description'      => "High performance {$category} modification for {$gModel->name}. Tested for maximum stability, compatibility, and zero crashes.",
                        'author'           => 'Nexus Creator ' . rand(10, 999),
                        'version'          => '1.' . rand(0, 9) . '.' . rand(0, 9),
                        'load_order'       => $i,
                        'nexus_url'        => "https://www.nexusmods.com/{$gModel->slug}/mods/" . rand(1000, 99999),
                        'download_url'     => "https://www.nexusmods.com/{$gModel->slug}/mods/" . rand(1000, 99999),
                        'image_url'        => $gModel->thumbnail,
                        'status'           => 'published',
                        'downloads_count'  => rand(1000, 95000),
                        'fps_impact'       => rand(0, 15),
                        'is_verified'      => true,
                    ]
                );

                if (!empty($gameVersionIds)) {
                    $mod->gameVersions()->syncWithoutDetaching($gameVersionIds);
                }
            }
        }

        // 5. Populate Default Ad Slots
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
