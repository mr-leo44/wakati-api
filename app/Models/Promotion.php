<?php

namespace App\Models;

use App\Models\Faculty;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'sigle',
        'faculty_id'
    ];

    public function getSigleAttribute(String $value)
    {
        return strtolower($value);
    }

    public function getRouteKeyName()
    {
        return 'sigle';
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

}
