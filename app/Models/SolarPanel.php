<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SolarPanel
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int $power_output
 * @property string $description
 * @property int $manufacturer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Manufacturer $manufacturer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel whereManufacturerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel wherePowerOutput($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolarPanel whereUpdatedAt($value)
 * @mixin \Eloquent
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
