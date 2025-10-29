<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ConnectorType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Connector> $connectors
 * @property-read int|null $connectors_count
 * @method static \Database\Factories\ConnectorTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConnectorType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConnectorType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConnectorType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConnectorType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConnectorType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConnectorType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConnectorType whereUpdatedAt($value)
 * @mixin \Eloquent
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
