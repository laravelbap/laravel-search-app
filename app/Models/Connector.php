<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Connector
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string $description
 * @property int $manufacturer_id
 * @property int $connector_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ConnectorType $connectorType
 * @property-read \App\Models\Manufacturer $manufacturer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector whereConnectorTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector whereManufacturerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connector whereUpdatedAt($value)
 * @mixin \Eloquent
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
