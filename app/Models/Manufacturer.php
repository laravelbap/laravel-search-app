<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Manufacturer
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Battery> $batteries
 * @property-read int|null $batteries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Connector> $connectors
 * @property-read int|null $connectors_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SolarPanel> $solarPanels
 * @property-read int|null $solar_panels_count
 * @method static \Database\Factories\ManufacturerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Manufacturer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Manufacturer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Manufacturer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Manufacturer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Manufacturer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Manufacturer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Manufacturer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function solarPanels()
    {
        return $this->hasMany(SolarPanel::class);
    }

    public function batteries()
    {
        return $this->hasMany(Battery::class);
    }

    public function connectors()
    {
        return $this->hasMany(Connector::class);
    }


}
