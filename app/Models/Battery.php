<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Battery
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property float $capacity
 * @property string $description
 * @property int $manufacturer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Manufacturer $manufacturer
 * @method static \Database\Factories\BatteryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery whereManufacturerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Battery whereUpdatedAt($value)
 * @mixin \Eloquent
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
