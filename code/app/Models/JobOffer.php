<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class JobOffer extends Model
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
        'description',
        'requirements',
        'project_type',
        'budget_min',
        'budget_max',
        'duration',
        'location_type',
        'location',
        'skills_required',
        'status',
        'featured',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'skills_required' => 'array',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the job offer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all applications for this job offer.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get the skills associated with this job offer.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_offer_skill')->withTimestamps();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobOffer) {
            if (empty($jobOffer->slug)) {
                $jobOffer->slug = Str::slug($jobOffer->title);
            }
            if (empty($jobOffer->published_at)) {
                $jobOffer->published_at = now();
            }
        });

        static::updating(function ($jobOffer) {
            if ($jobOffer->isDirty('title')) {
                $jobOffer->slug = Str::slug($jobOffer->title);
            }
        });
    }

    /**
     * Scope a query to only include active job offers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured job offers.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to only include published job offers.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Check if job offer is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if job offer is filled.
     */
    public function isFilled(): bool
    {
        return $this->status === 'filled';
    }

    /**
     * Check if job offer is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if job offer is featured.
     */
    public function isFeatured(): bool
    {
        return $this->featured === true;
    }

    /**
     * Get formatted budget range.
     */
    public function getBudgetRangeAttribute(): string
    {
        if ($this->budget_min && $this->budget_max) {
            return "$" . number_format($this->budget_min, 0) . " - $" . number_format($this->budget_max, 0);
        }

        if ($this->budget_min) {
            return "From $" . number_format($this->budget_min, 0);
        }

        if ($this->budget_max) {
            return "Up to $" . number_format($this->budget_max, 0);
        }

        return 'Negotiable';
    }

    /**
     * Get pending applications count.
     */
    public function getPendingApplicationsCountAttribute(): int
    {
        return $this->applications()->where('status', 'pending')->count();
    }

    /**
     * Get total applications count.
     */
    public function getTotalApplicationsCountAttribute(): int
    {
        return $this->applications()->count();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
