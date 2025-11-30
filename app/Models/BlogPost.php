<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Table\TableExtension;

class BlogPost extends Model
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
        'slug',
        'content',
        'featured_image',
        'published_at',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the blog post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the tags for the blog post.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blogPost) {
            if (empty($blogPost->slug)) {
                $blogPost->slug = Str::slug($blogPost->title);
            }
        });

        static::updating(function ($blogPost) {
            if ($blogPost->isDirty('title')) {
                $blogPost->slug = Str::slug($blogPost->title);
            }
        });
    }

    /**
     * Scope a query to only include published blog posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include draft blog posts.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include archived blog posts.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope a query to order blog posts by published date descending.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Check if blog post is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published'
               && $this->published_at
               && $this->published_at->isPast();
    }

    /**
     * Check if blog post is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Get excerpt from content.
     */
    public function getExcerptAttribute($length = 150): string
    {
        return Str::limit(strip_tags($this->content), $length);
    }

    /**
     * Get reading time estimate.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return ceil($wordCount / 200); // Average reading speed
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Convert Markdown content to HTML.
     */
    public function getContentHtmlAttribute(): string
    {
        if (empty($this->content)) {
            return '';
        }

        // Create a new environment with all the CommonMark parsers/renderers
        $environment = new Environment([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 100,
        ]);

        // Add the extensions
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new TableExtension());

        // Create the converter
        $converter = new CommonMarkConverter([], $environment);

        return $converter->convert($this->content)->getContent();
    }

    /**
     * Check if content is in Markdown format.
     */
    public function isMarkdown(): bool
    {
        // Simple check for common Markdown syntax
        return preg_match('/^#|\*\*|__|\[.*\]\(.*\)|```/', $this->content) === 1;
    }

    /**
     * Get rendered content (HTML or Markdown converted to HTML).
     */
    public function getRenderedContentAttribute(): string
    {
        if ($this->isMarkdown()) {
            return $this->content_html;
        }

        return $this->content;
    }

    /**
     * Extract Table of Contents from Markdown content.
     */
    public function getTableOfContentsAttribute(): array
    {
        if (!$this->isMarkdown()) {
            return [];
        }

        $toc = [];
        $lines = explode("\n", $this->content);

        foreach ($lines as $line) {
            if (preg_match('/^(#{1,6})\s+(.+)$/', $line, $matches)) {
                $level = strlen($matches[1]);
                $title = trim($matches[2]);
                $slug = Str::slug($title);

                $toc[] = [
                    'level' => $level,
                    'title' => $title,
                    'slug' => $slug,
                ];
            }
        }

        return $toc;
    }
}