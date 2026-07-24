<?php

namespace Tests\Feature;

use App\Models\Education;
use App\Models\Experience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackgroundAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_guests_cannot_reach_the_background_pages(): void
    {
        $this->get(route('admin.education.index'))->assertRedirect(route('login'));
        $this->get(route('admin.experiences.index'))->assertRedirect(route('login'));
    }

    public function test_education_can_be_created_updated_and_deleted(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.education.store'), [
                'degree' => 'MSc Computer Science',
                'field_of_study' => 'Software Engineering',
                'institution' => 'University of Batna',
                'start_date' => '2018-09-01',
                'end_date' => '2020-06-30',
            ])
            ->assertRedirect(route('admin.education.index'));

        $entry = Education::firstWhere('degree', 'MSc Computer Science');
        $this->assertNotNull($entry);
        $this->assertSame($admin->id, $entry->user_id);
        $this->assertFalse($entry->is_current);

        $this->actingAs($admin)
            ->put(route('admin.education.update', $entry), [
                'degree' => 'PhD Computer Science',
                'field_of_study' => 'Distributed Systems',
                'institution' => 'University of Batna',
                'start_date' => '2020-10-01',
                'is_current' => '1',
                'end_date' => '2030-01-01',
            ])
            ->assertRedirect(route('admin.education.index'));

        $entry->refresh();
        $this->assertSame('PhD Computer Science', $entry->degree);
        $this->assertTrue($entry->is_current);
        // A current course of study must not keep a stale end date.
        $this->assertNull($entry->end_date);

        $this->actingAs($admin)->delete(route('admin.education.destroy', $entry));
        $this->assertDatabaseMissing('education', ['id' => $entry->id]);
    }

    public function test_education_validation_rejects_an_end_date_before_the_start(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.education.store'), [
                'degree' => 'BSc',
                'field_of_study' => 'CS',
                'institution' => 'Somewhere',
                'start_date' => '2020-01-01',
                'end_date' => '2019-01-01',
            ])
            ->assertSessionHasErrors('end_date');

        $this->assertSame(0, Education::count());
    }

    public function test_experience_can_be_created_updated_and_deleted(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.experiences.store'), [
                'position' => 'Backend Developer',
                'company' => 'Acme',
                'description' => 'Built APIs.',
                'employment_type' => 'full-time',
                'start_date' => '2021-01-01',
                'end_date' => '2023-01-01',
            ])
            ->assertRedirect(route('admin.experiences.index'));

        $entry = Experience::firstWhere('position', 'Backend Developer');
        $this->assertNotNull($entry);
        $this->assertSame($admin->id, $entry->user_id);

        $this->actingAs($admin)
            ->put(route('admin.experiences.update', $entry), [
                'position' => 'Lead Backend Developer',
                'company' => 'Acme',
                'description' => 'Led the API team.',
                'start_date' => '2021-01-01',
                'is_current' => '1',
            ])
            ->assertRedirect(route('admin.experiences.index'));

        $entry->refresh();
        $this->assertSame('Lead Backend Developer', $entry->position);
        $this->assertTrue($entry->is_current);
        $this->assertNull($entry->end_date);

        $this->actingAs($admin)->delete(route('admin.experiences.destroy', $entry));
        $this->assertDatabaseMissing('experiences', ['id' => $entry->id]);
    }

    public function test_years_of_experience_comes_from_the_profile_field(): void
    {
        $admin = $this->admin();

        // Not set: the stat is hidden entirely rather than guessed.
        $this->get(route('home'))->assertOk()->assertDontSee('Years Experience');

        $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'years_experience' => 8,
            ])
            ->assertRedirect(route('admin.profile.edit'));

        $this->assertSame(8, $admin->fresh()->years_experience);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Years Experience')
            ->assertSee('8+');
    }

    public function test_background_entries_render_on_the_homepage(): void
    {
        $admin = $this->admin();
        $admin->update(['years_experience' => 8]);

        Education::create([
            'user_id' => $admin->id,
            'degree' => 'MSc Computer Science',
            'field_of_study' => 'Software Engineering',
            'institution' => 'University of Batna',
            'start_date' => '2018-09-01',
            'end_date' => '2020-06-30',
        ]);

        Experience::create([
            'user_id' => $admin->id,
            'position' => 'Backend Developer',
            'company' => 'Acme',
            'description' => 'Built APIs.',
            'start_date' => '2021-01-01',
            'is_current' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('MSc Computer Science')
            ->assertSee('University of Batna')
            ->assertSee('Backend Developer')
            ->assertSee('Years Experience');
    }
}
