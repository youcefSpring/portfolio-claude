<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PublicationLinkTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(public_path('documents/publications'));

        parent::tearDown();
    }

    public function test_link_url_prefers_doi_then_url_then_external_link(): void
    {
        // A DOI is the canonical address, so it wins over everything else.
        $withDoi = new Publication([
            'external_link' => 'https://example.com/external',
            'url' => 'https://example.com/url',
            'doi' => '10.1109/tas.2024.8.2.112',
        ]);
        $this->assertSame('https://doi.org/10.1109/tas.2024.8.2.112', $withDoi->link_url);

        // No DOI: use the publication URL.
        $withUrl = new Publication([
            'external_link' => 'https://example.com/external',
            'url' => 'https://example.com/url',
        ]);
        $this->assertSame('https://example.com/url', $withUrl->link_url);

        // Neither: fall back to the legacy column.
        $withExternal = new Publication(['external_link' => 'https://example.com/external']);
        $this->assertSame('https://example.com/external', $withExternal->link_url);

        // A DOI already given as a full URL is passed through untouched.
        $withFullDoi = new Publication(['doi' => 'https://doi.org/10.1109/x']);
        $this->assertSame('https://doi.org/10.1109/x', $withFullDoi->link_url);

        $this->assertNull((new Publication(['title' => 'No links']))->link_url);
    }

    public function test_url_saved_from_the_admin_form_is_linked_on_the_homepage(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.publications.store'), [
                'title' => 'Secure Element Integration',
                'authors' => 'Y. Benaissa',
                'type' => 'journal',
                'status' => 'published',
                'year' => 2024,
                'url' => 'https://example.com/paper.pdf',
                // no DOI on this one — the URL must be used
            ])
            ->assertRedirect(route('admin.publications.index'));

        $publication = Publication::firstWhere('title', 'Secure Element Integration');
        $this->assertSame('https://example.com/paper.pdf', $publication->url);

        // The homepage used to read external_link only, so this link never rendered.
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('https://example.com/paper.pdf', false);
    }

    public function test_doi_alone_is_linked_on_the_homepage(): void
    {
        $admin = $this->admin();

        Publication::create([
            'user_id' => $admin->id,
            'title' => 'Cryptographic Techniques',
            'authors' => 'Y. Benaissa',
            'type' => 'journal',
            'status' => 'published',
            'year' => 2024,
            'doi' => '10.1109/tas.2024.8.2.112',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('https://doi.org/10.1109/tas.2024.8.2.112', false);
    }

    public function test_a_publication_without_a_link_shows_no_read_more(): void
    {
        $admin = $this->admin();

        Publication::create([
            'user_id' => $admin->id,
            'title' => 'Unlinked Paper',
            'authors' => 'Y. Benaissa',
            'type' => 'journal',
            'status' => 'published',
            'year' => 2024,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Unlinked Paper')
            ->assertDontSee('Read More');
    }

    public function test_uploaded_pdf_is_stored_publicly_and_reachable(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.publications.store'), [
                'title' => 'Paper With PDF',
                'authors' => 'Y. Benaissa',
                'type' => 'journal',
                'status' => 'published',
                'year' => 2024,
                'publication_file' => UploadedFile::fake()->create('paper.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('admin.publications.index'));

        $publication = Publication::firstWhere('title', 'Paper With PDF');

        $this->assertStringStartsWith('documents/publications/', $publication->publication_file_path);
        $this->assertFileExists(public_path($publication->publication_file_path));
        $this->assertSame(asset($publication->publication_file_path), $publication->file_url);
    }
}
