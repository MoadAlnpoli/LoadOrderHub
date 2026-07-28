<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\ModPack;
use App\Models\Mod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the multi-language selector changes session locale.
     */
    public function test_multi_language_selector_saves_in_session(): void
    {
        $response = $this->get('/?lang=es');
        $response->assertSessionHas('locale', 'es');
        
        $response = $this->get('/?lang=de');
        $response->assertSessionHas('locale', 'de');
    }

    /**
     * Test that SQL Injection keywords in search query are handled safely by Eloquent.
     */
    public function test_sql_injection_protection_in_search(): void
    {
        $response = $this->get('/api/search?q=foo\' OR \'1\'=\'1');
        $response->assertStatus(200);
    }

    /**
     * Test export load order returns the correct plain text output.
     */
    public function test_export_load_order_returns_txt(): void
    {
        $user = User::factory()->create();
        $game = Game::create(['name' => 'Skyrim', 'slug' => 'skyrim', 'thumbnail' => 'http://example.com/thumb.jpg']);
        $version = GameVersion::create(['game_id' => $game->id, 'version' => '1.6.640']);
        
        $modPack = ModPack::create([
            'title_en' => 'Test Pack',
            'title_ar' => 'تجميعة تجريبية',
            'description_en' => 'Desc',
            'description_ar' => 'وصف',
            'creator_id' => $user->id,
            'is_published' => true
        ]);
        $modPack->gameVersions()->attach($version->id);

        $mod = Mod::create([
            'name' => 'SkyUI',
            'slug' => 'skyui',
            'load_order' => 1,
            'game_id' => $game->id,
            'mod_pack_id' => $modPack->id,
        ]);

        $response = $this->get("/modpacks/{$modPack->id}/export");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('SkyUI');
    }

    /**
     * Test that authenticated users can create modpacks.
     */
    public function test_authenticated_user_can_create_modpack(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $game = Game::create(['name' => 'Skyrim', 'slug' => 'skyrim', 'thumbnail' => 'http://example.com/thumb.jpg']);
        $version = GameVersion::create(['game_id' => $game->id, 'version' => '1.6.640']);

        $response = $this->actingAs($user)->post('/modpacks', [
            'title_en' => 'Awesome New Load Order',
            'description_en' => 'This is a test description.',
            'game_version_id' => $version->id,
            'mods' => [
                ['name' => 'Mod A', 'load_order' => 1],
                ['name' => 'Mod B', 'load_order' => 2]
            ]
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('mod_packs', [
            'title_en' => 'Awesome New Load Order',
            'created_by' => $user->id
        ]);

        $this->assertDatabaseHas('mods', [
            'name' => 'Mod A',
            'game_id' => $game->id
        ]);
    }

    /**
     * Test registration fails without terms accepted.
     */
    public function test_registration_fails_without_terms(): void
    {
        $this->withoutMiddleware();

        $response = $this->post('/register', [
            'name' => 'John Doe New',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            // 'terms' omitted on purpose
        ]);

        $response->assertSessionHasErrors(['terms']);
    }

    /**
     * Test forgot password simulator screen loads.
     */
    public function test_forgot_password_simulates_link(): void
    {
        $this->withoutMiddleware();
        $user = User::factory()->create(['email' => 'target@example.com']);

        $response = $this->post('/forgot-password', [
            'email' => 'target@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Reset Link Simulated!');
    }

    /**
     * Test search nexus route works.
     */
    public function test_search_nexus_route(): void
    {
        $this->withoutMiddleware();
        $user = User::factory()->create(['is_admin' => true]);
        $game = Game::create(['name' => 'Skyrim', 'slug' => 'skyrim', 'thumbnail' => 'http://example.com/thumb.jpg']);

        $response = $this->actingAs($user)->get("/admin/nexus/search?game_id={$game->id}&q=SkyUI");
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'results']);
    }

    /**
     * Test quick add mod route inserts mod into database.
     */
    public function test_quick_add_mod_inserts_into_db(): void
    {
        $this->withoutMiddleware();
        $user = User::factory()->create(['is_admin' => true]);
        $game = Game::create(['name' => 'Skyrim', 'slug' => 'skyrim', 'thumbnail' => 'http://example.com/thumb.jpg']);
        $version = GameVersion::create(['game_id' => $game->id, 'version' => '1.6.640']);

        $response = $this->actingAs($user)->post("/admin/mods/quick-add", [
            'game_id' => $game->id,
            'name' => 'Test Scraped Mod',
            'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/12604'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('mods', [
            'name' => 'Test Scraped Mod',
            'game_id' => $game->id,
            'nexus_url' => 'https://www.nexusmods.com/skyrimspecialedition/mods/12604'
        ]);
    }

    /**
     * Test admin can manually add and delete game versions.
     */
    public function test_admin_can_add_and_delete_game_version_manually(): void
    {
        $this->withoutMiddleware();
        $user = User::factory()->create(['is_admin' => true]);
        $game = Game::create(['name' => 'Elden Ring', 'slug' => 'elden-ring']);

        // Test creation
        $response = $this->actingAs($user)->post('/admin/game-versions', [
            'game_id' => $game->id,
            'version' => '1.10.1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('game_versions', [
            'game_id' => $game->id,
            'version' => '1.10.1',
        ]);

        $gv = GameVersion::where('game_id', $game->id)->where('version', '1.10.1')->first();

        // Test deletion
        $delResponse = $this->actingAs($user)->delete("/admin/game-versions/{$gv->id}");
        $delResponse->assertRedirect();

        $this->assertDatabaseMissing('game_versions', [
            'id' => $gv->id,
        ]);
    }
}
