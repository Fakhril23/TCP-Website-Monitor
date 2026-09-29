<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
      protected $fillable = [
        'name',
        'description',
        'url',
        'skpd_id',
        'photo',
        'status_http',
        'status_https',
        'status',
        'last_check'
    ];

    public function skpd(): BelongsTo
    {
        return $this->belongsTo(Skpd::class);
    }

    public function testings(): HasMany
    {
        return $this->hasMany(Testing::class);
    }
}
