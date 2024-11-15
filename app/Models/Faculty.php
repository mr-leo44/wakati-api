<?php

namespace App\Models;

use App\Models\University;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Faculty extends Model
{
    protected $fillable = [
        'name',
        'sigle'
    ];

    public function getSigleAttribute(String $value)
    {
        return strtolower($value);
    }

    public function getRouteKeyName()
    {
        return 'sigle';
    }

    public function universities(): BelongsToMany
    {
        return $this->belongsToMany(University::class);
    }
}
