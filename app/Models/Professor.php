<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Professor extends Model
{
    protected $guarded = [];

    public function universities(): BelongsToMany
    {
        return $this->belongsToMany(University::class);
    }

    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }
}
