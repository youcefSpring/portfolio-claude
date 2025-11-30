<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class JobApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_offer_id',
        'full_name',
        'email',
        'phone',
        'cv_file_path',
        'cover_letter',
        'status',
        'notes',
        'applied_at',
        'reviewed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * Get the job offer this application belongs to.
     */
    public function jobOffer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            if (empty($application->applied_at)) {
                $application->applied_at = now();
            }
        });

        // Delete CV file when application is deleted
        static::deleting(function ($application) {
            if ($application->cv_file_path) {
                $filePath = public_path($application->cv_file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        });
    }

    /**
     * Scope a query to only include pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include reviewed applications.
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Scope a query to only include shortlisted applications.
     */
    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    /**
     * Scope a query to only include rejected applications.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include accepted applications.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Check if application is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application is reviewed.
     */
    public function isReviewed(): bool
    {
        return $this->status === 'reviewed';
    }

    /**
     * Check if application is shortlisted.
     */
    public function isShortlisted(): bool
    {
        return $this->status === 'shortlisted';
    }

    /**
     * Check if application is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if application is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Mark application as reviewed.
     */
    public function markAsReviewed(): void
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Get CV download URL.
     */
    public function getCvUrlAttribute(): string
    {
        if ($this->cv_file_path) {
            return asset($this->cv_file_path);
        }

        return '';
    }

    /**
     * Get CV path attribute (alias for cv_file_path).
     */
    public function getCvPathAttribute(): ?string
    {
        return $this->cv_file_path;
    }

    /**
     * Get CV file name.
     */
    public function getCvFileNameAttribute(): string
    {
        if ($this->cv_file_path) {
            return basename($this->cv_file_path);
        }

        return '';
    }

    /**
     * Get status badge color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'reviewed' => 'blue',
            'shortlisted' => 'purple',
            'rejected' => 'red',
            'accepted' => 'green',
            default => 'gray',
        };
    }

    /**
     * Get status badge label.
     */
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }
}
