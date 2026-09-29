<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skpd extends Model
{
    protected $fillable = [
        'title'
    ];

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }
}
