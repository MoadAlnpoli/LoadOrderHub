<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\ModPack;
use App\Models\Mod;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModPackTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all migrations compile and Eloquent relationships work properly.
     */
    public function test_mod_pack_relationships_and_database_schema(): void
    {
        // Create user
        $user = User::factory()->create([
            'name' => 'Gamer John',
            'email' => 'john@gamer.com',
            'is_admin' => false,
        ]);

        // Create a Game
        $game = Game::create([
            'name' => 'Skyrim SE',
            'slug' => 'skyrim-se',
            'description' => 'Fantasy RPG',
        ]);

        // Create a Game Version
        $version = GameVersion::create([
            'game_id' => $game->id,
            'version' => '1.6.640',
        ]);

        // Create a Mod Pack
        $modPack = ModPack::create([
            'game_version_id' => $version->id,
            'title_en' => 'Awesome Skyrim AE Load Order',
            'title_ar' => 'تجميعة مودات سكاي ريم الرائعة',
            'description_en' => 'Graphics load order',
            'description_ar' => 'ترتيب مودات الرسومات',
            'youtube_video_id' => 'dQw4w9WgXcQ',
            'created_by' => $user->id,
        ]);

        // Create a Mod in the pack
        $mod = Mod::create([
            'mod_pack_id' => $modPack->id,
            'name' => 'SkyUI',
            'load_order' => 1,
            'nexus_url' => 'https://nexusmods.com',
        ]);

        // Create a Comment
        $comment = Comment::create([
            'mod_pack_id' => $modPack->id,
            'user_id' => $user->id,
            'content' => 'Will this crash with other mods?',
        ]);

        // Create a reply Comment
        $reply = Comment::create([
            'mod_pack_id' => $modPack->id,
            'user_id' => $user->id,
            'parent_id' => $comment->id,
            'content' => 'No, it is highly stable!',
        ]);

        // Create a Rating
        $rating = Rating::create([
            'user_id' => $user->id,
            'mod_pack_id' => $modPack->id,
            'is_upvote' => true,
        ]);

        // Assert Database Entries Exist
        $this->assertDatabaseHas('games', ['slug' => 'skyrim-se']);
        $this->assertDatabaseHas('game_versions', ['version' => '1.6.640']);
        $this->assertDatabaseHas('mod_packs', ['title_en' => 'Awesome Skyrim AE Load Order']);
        $this->assertDatabaseHas('mods', ['name' => 'SkyUI', 'load_order' => 1]);
        $this->assertDatabaseHas('comments', ['content' => 'Will this crash with other mods?']);
        $this->assertDatabaseHas('comments', ['parent_id' => $comment->id, 'content' => 'No, it is highly stable!']);
        $this->assertDatabaseHas('ratings', ['user_id' => $user->id, 'is_upvote' => true]);

        // Verify Relationships
        $this->assertEquals(1, $game->versions->count());
        $this->assertEquals('1.6.640', $game->versions->first()->version);
        $this->assertEquals($game->id, $version->game->id);
        $this->assertEquals(1, $version->modPacks->count());
        $this->assertEquals($version->id, $modPack->gameVersion->id);
        $this->assertEquals(1, $modPack->mods->count());
        $this->assertEquals('SkyUI', $modPack->mods->first()->name);
        $this->assertEquals($modPack->id, $mod->modPack->id);
        $this->assertEquals(2, $modPack->comments->count());
        $this->assertEquals(2, $user->comments->count());
        $this->assertEquals(1, $user->ratings->count());
        $this->assertEquals(1, $user->modPacks->count());
        $this->assertEquals(1, $comment->replies->count());
        $this->assertEquals('No, it is highly stable!', $comment->replies->first()->content);
        $this->assertEquals($comment->id, $reply->parent->id);
    }

    /**
     * Test admin dashboard permissions.
     */
    public function test_admin_dashboard_security(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Guest is redirected to home page
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('home'));

        // Normal user is redirected to home
        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertRedirect(route('home'));

        // Admin user gets access
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    /**
     * Test user profile and bookmarking mechanics.
     */
    public function test_user_profile_and_bookmarking(): void
    {
        $user = User::factory()->create();
        
        $game = Game::create(['name' => 'Witcher 3', 'slug' => 'witcher-3']);
        $version = GameVersion::create(['game_id' => $game->id, 'version' => '4.0']);
        $modPack = ModPack::create([
            'game_version_id' => $version->id,
            'title_en' => 'Witcher 3 next-gen list',
            'title_ar' => 'مودات ويتشر 3',
        ]);

        // Visit profile (creates profile entry automatically)
        $response = $this->actingAs($user)->get(route('profile'));
        $response->assertStatus(200);
        $this->assertDatabaseHas('profiles', ['user_id' => $user->id]);

        // Toggle bookmark (Save)
        $response = $this->actingAs($user)->post(route('modpacks.save', $modPack->id));
        $response->assertStatus(200)->assertJson(['success' => true, 'saved' => true]);

        // Assert pack is in JSON bookmarks array
        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertContains($modPack->id, $profile->saved_packs);

        // Toggle bookmark again (Unsave)
        $response = $this->actingAs($user)->post(route('modpacks.save', $modPack->id));
        $response->assertStatus(200)->assertJson(['success' => true, 'saved' => false]);
    }
}
