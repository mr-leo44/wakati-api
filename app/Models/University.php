<?php

namespace App\Models;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class University extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sigle',
        'email',
        'localisation',
        'address',
        'phone',
        'website',
    ];

    public function getSigleAttribute(String $value)
    {
        return strtolower($value);
    }

    public function getRouteKeyName()
    {
        return 'sigle';
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class);
    }
}
