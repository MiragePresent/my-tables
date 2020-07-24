<?php

namespace App;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property string $type
 * @property int $originalAmount
 * @property int $typeCode
 *
 * @property-read User $user
 *
 * @method static Builder latest()
 */
class Transaction extends Model
{
    use Timestamp;

    public const MORPH_NAME = 'transaction';

    public const TYPE_CODE_CREDIT = 0;
    public const TYPE_CODE_DEBIT = 1;
    public const TYPE_CREDIT = 'credit';
    public const TYPE_DEBIT = 'debit';

    private const AVAILABLE_TYPES = [
        self::TYPE_CODE_CREDIT => self::TYPE_CREDIT,
        self::TYPE_CODE_DEBIT => self::TYPE_DEBIT,
    ];

    protected $fillable = [
        'user_id',
        'amount',
        'type',
    ];

//    public static function booted()
//    {
//        static::addGlobalScope(new LatestScope());
//    }

    // Relations

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getOriginalAmountAttribute(): int
    {
        return $this->getOriginal('amount');
    }

    public function getAmountAttribute($value): float
    {
        return $value / 100;
    }

    public function getTypeCodeAttribute(): int
    {
        return $this->getOriginal('type');
    }

    public function getTypeAttribute($value): string
    {
        return self::AVAILABLE_TYPES[$value] ?? 'invalid';
    }

    // Mutators

    public function setTypeAttribute(string $value)
    {
        $unified = strtolower($value);

        if (!in_array($unified, self::AVAILABLE_TYPES)) {
            throw new InvalidArgumentException(sprintf('Given transaction type [%s] is not valid', $value));
        }

        $this->attributes['type'] = array_search($unified, self::AVAILABLE_TYPES);
    }

    public function setTypeCodeAttribute(int $value)
    {
        if (!isset(self::AVAILABLE_TYPES[$value])) {
            throw new InvalidArgumentException(sprintf('Given transaction type code [%s] is not valid', $value));
        }

        $this->attributes['type'] = $value;
    }
}
