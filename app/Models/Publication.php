<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Publication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'authors',
        'type',
        'status',
        'journal',
        'conference',
        'venue',
        'volume',
        'issue',
        'pages',
        'year',
        'doi',
        'url',
        'abstract',
        'description',
        'keywords',
        'publication_file_path',
        'external_link',
    ];

    /**
     * Best public link for the publication.
     *
     * A DOI is the canonical, permanent address, so it wins when present.
     * Otherwise fall back to the plain URL, then to the legacy external_link
     * column that older rows still use.
     */
    public function getLinkUrlAttribute(): ?string
    {
        if (filled($this->doi)) {
            return \Illuminate\Support\Str::startsWith($this->doi, ['http://', 'https://'])
                ? $this->doi
                : 'https://doi.org/' . ltrim($this->doi, '/');
        }

        foreach ([$this->url, $this->external_link] as $link) {
            if (filled($link)) {
                return $link;
            }
        }

        return null;
    }

    /**
     * Public URL for an uploaded publication PDF, wherever it was stored.
     */
    public function getFileUrlAttribute(): ?string
    {
        if (! $this->publication_file_path) {
            return null;
        }

        return file_exists(public_path($this->publication_file_path))
            ? asset($this->publication_file_path)
            : asset('storage/' . $this->publication_file_path);
    }

    /**
     * Get the user that owns the publication.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the tags for the publication.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    /**
     * Scope a query to order publications by year descending.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('year', 'desc');
    }

    /**
     * Scope a query to filter publications by year.
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope a query to filter publications by journal.
     */
    public function scopeByJournal($query, $journal)
    {
        return $query->where('journal', 'like', "%{$journal}%");
    }

    /**
     * Get the formatted citation for the publication.
     */
    public function getCitationAttribute(): string
    {
        $citation = $this->authors . ' (' . $this->year . '). ' . $this->title . '.';

        if ($this->journal) {
            $citation .= ' ' . $this->journal . '.';
        } elseif ($this->conference) {
            $citation .= ' In ' . $this->conference . '.';
        }

        return $citation;
    }

    /**
     * Check if publication has a downloadable file.
     */
    public function hasFile(): bool
    {
        return !empty($this->publication_file_path);
    }

    /**
     * Check if publication has an external link.
     */
    public function hasExternalLink(): bool
    {
        return !empty($this->external_link);
    }
}
