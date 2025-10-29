<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Battery
 */
class Battery extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'manufacturer_id',
        'price',
        'capacity',
        'description'
    ];

    protected $casts = [
        'capacity' => 'double',
        'price' => 'double',
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

}
