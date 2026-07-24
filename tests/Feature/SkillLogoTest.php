<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SkillLogoTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_skill_is_created_with_a_picked_logo(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.skills.store'), [
                'name' => 'Laravel',
                'simple_icon' => 'laravel',
                'is_featured' => '1',
            ])
            ->assertRedirect(route('admin.skills.index'));

        $skill = Skill::firstWhere('name', 'Laravel');

        $this->assertNotNull($skill);
        $this->assertSame('laravel', $skill->simple_icon);
        $this->assertTrue($skill->is_featured);
        $this->assertSame('laravel', $skill->slug);
        // Defaults fill in for the fields the simplified form no longer asks about.
        $this->assertSame('other', $skill->category);
        $this->assertSame(3, $skill->proficiency_level);
    }

    public function test_skill_is_created_with_an_uploaded_logo(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->post(route('admin.skills.store'), [
                'name' => 'In House Tool',
                'logo' => UploadedFile::fake()->image('tool.png'),
            ])
            ->assertRedirect(route('admin.skills.index'));

        $skill = Skill::firstWhere('name', 'In House Tool');

        $this->assertNotNull($skill->logo);
        Storage::disk('public')->assertExists($skill->logo);
    }

    public function test_picked_logo_is_updated(): void
    {
        $skill = Skill::create([
            'name' => 'React',
            'slug' => 'react',
            'category' => 'other',
            'proficiency_level' => 3,
            'simple_icon' => 'react',
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.skills.update', $skill), [
                'name' => 'React',
                'simple_icon' => 'vuedotjs',
            ])
            ->assertRedirect(route('admin.skills.index'));

        $this->assertSame('vuedotjs', $skill->fresh()->simple_icon);
        $this->assertFalse($skill->fresh()->is_featured);
    }

    public function test_uploaded_logo_replaces_the_old_file_and_can_be_removed(): void
    {
        Storage::fake('public');

        $skill = Skill::create([
            'name' => 'Docker',
            'slug' => 'docker',
            'category' => 'other',
            'proficiency_level' => 3,
            'logo' => UploadedFile::fake()->image('old.png')->store('images/skills', 'public'),
        ]);
        $oldLogo = $skill->logo;

        $this->actingAs($this->admin())
            ->put(route('admin.skills.update', $skill), [
                'name' => 'Docker',
                'logo' => UploadedFile::fake()->image('new.png'),
            ])
            ->assertRedirect(route('admin.skills.index'));

        $newLogo = $skill->fresh()->logo;
        $this->assertNotSame($oldLogo, $newLogo);
        Storage::disk('public')->assertMissing($oldLogo);
        Storage::disk('public')->assertExists($newLogo);

        $this->actingAs($this->admin())
            ->put(route('admin.skills.update', $skill), [
                'name' => 'Docker',
                'remove_logo' => '1',
            ]);

        $this->assertNull($skill->fresh()->logo);
        Storage::disk('public')->assertMissing($newLogo);
    }

    public function test_featured_skill_logo_renders_on_the_homepage(): void
    {
        User::factory()->create(['role' => 'admin']);

        Skill::create([
            'name' => 'PHP',
            'slug' => 'php',
            'category' => 'other',
            'proficiency_level' => 3,
            'simple_icon' => 'php',
            'is_featured' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('PHP')
            ->assertSee('simple-icons@latest/icons/php.svg', false);
    }
}
