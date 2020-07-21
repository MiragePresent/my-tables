<?php

namespace App;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $entity_id
 * @property string $entity_type Morph relation name
 * @property string $title
 * @property string $description
 *
 * @property-read User|Transaction $entity
 */
class Note extends Model
{
    use Timestamp;

    protected $fillable = [
        'entity_id',
        'entity_type',
        'title',
        'description',
        'create_at',
        'updated_at',
    ];

    public function entity()
    {
        return $this->morphTo('entity_type');
    }
}
