<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // MUST add this at the top

class Website extends Model
{
    protected $guarded = []; // Or your $fillable array

    // 1. Auto-generate UUID when adding a new website
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($website) {
            $website->uuid = (string) Str::uuid();
        });
    }

    // 2. Tell Laravel to use 'uuid' instead of 'id' in URLs
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}