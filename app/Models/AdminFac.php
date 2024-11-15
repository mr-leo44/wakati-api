<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminFac extends Model
{
    protected $fillable = [
        'faculty_id',
        'university_id'
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }
}
