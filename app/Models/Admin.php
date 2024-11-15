<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $fillable = [
        'university_id'
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }
}
