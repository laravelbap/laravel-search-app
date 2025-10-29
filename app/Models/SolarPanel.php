<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SolarPanel
 */
class SolarPanel extends Model
{
    protected $fillable = [
        'name',
        'manufacturer_id',
        'price',
        'power_output',
        'description'
    ];

    protected $casts = [
        'power_output' => 'integer',
        'price' => 'double',
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
