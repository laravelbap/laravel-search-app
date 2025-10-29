<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ConnectorType
 */
class ConnectorType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function connectors()
    {
        return $this->hasMany(Connector::class);
    }
}
