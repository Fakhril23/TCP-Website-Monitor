<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testing extends Model
{
     protected $fillable = [
        'website_id',
        'status_http',
        'status_https',
        'status',
        'response_time',
        'timestamp',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
