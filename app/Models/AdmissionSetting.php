<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionSetting extends Model
{
    protected $fillable = [
        'is_open',
        'start_date',
        'end_date',
        'announcement_message',
    ];

    protected function casts(): array
    {
        return [
            'is_open' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'is_open' => true,
            'announcement_message' => 'أهلاً بك في بوابة القبول والتسجيل للعام الأكاديمي الجديد.',
        ]);
    }

    public function isCurrentlyOpen(): bool
    {
        if (!$this->is_open) {
            return false;
        }

        $today = now()->startOfDay();

        if ($this->start_date && $today->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $today->gt($this->end_date)) {
            return false;
        }

        return true;
    }
}
