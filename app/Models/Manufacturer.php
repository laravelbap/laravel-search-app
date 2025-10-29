<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Manufacturer
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
