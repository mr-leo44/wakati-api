<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\AdminFac;
use App\Models\Professor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function professors(): BelongsToMany
    {
        return $this->belongsToMany(Professor::class);
    }

    public function adminFacs(): HasMany
    {
        return $this->hasMany(AdminFac::class);
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }

    
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

}
