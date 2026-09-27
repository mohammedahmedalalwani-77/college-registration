<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Major extends Model
{
    protected $fillable = [
        'name',
        'faculty',
        'min_gpa',
        'capacity',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function approvedApplicationsCount(): int
    {
        return $this->applications()->where('status', 'approved')->count();
    }

    public function availableCapacity(): int
    {
        return max(0, $this->capacity - $this->approvedApplicationsCount());
    }

    public function isFull(): bool
    {
        return $this->availableCapacity() <= 0;
    }
}

