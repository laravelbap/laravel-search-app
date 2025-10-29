<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Connector
 */
class Connector extends Model
{
    protected $fillable = [
        'name',
        'manufacturer_id',
        'price',
        'connector_type_id',
        'description'
    ];

    protected $casts = [
        'price' => 'double',
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function connectorType(): BelongsTo
    {
        return $this->belongsTo(ConnectorType::class);
    }
}
