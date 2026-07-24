<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'proficiency_level',
        'icon',
        'logo',
        'simple_icon',
        'color',
        'is_featured',
        'years_experience',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'proficiency_level' => 'integer',
        'years_experience' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Public URL for an uploaded skill logo, wherever it was uploaded to.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return file_exists(public_path($this->logo))
            ? asset($this->logo)
            : asset('storage/' . $this->logo);
    }

    public function getProficiencyLabelAttribute()
    {
        return match($this->proficiency_level) {
            1 => 'Beginner',
            2 => 'Novice',
            3 => 'Intermediate',
            4 => 'Advanced',
            5 => 'Expert',
            default => 'Unknown'
        };
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'programming' => 'Programming Language',
            'framework' => 'Framework/Library',
            'database' => 'Database',
            'tool' => 'Tool/Software',
            'design' => 'Design',
            'soft_skill' => 'Soft Skill',
            default => 'Other'
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($skill) {
            if (empty($skill->slug)) {
                $skill->slug = Str::slug($skill->name);
            }
        });

        static::updating(function ($skill) {
            if ($skill->isDirty('name') && empty($skill->slug)) {
                $skill->slug = Str::slug($skill->name);
            }
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }
}
