<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use InvalidArgumentException;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property int $roleCode
 *
 * @property-read Transaction[] $transactions
 *
 * @method static Builder customers() Select only customers
 * @method static Builder latest()
 */
class User extends Authenticatable
{
    use Notifiable;

    public const MORPH_NAME = 'user';

    public const ROLE_ADMIN = 'admin';
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_CODE_ADMIN = 1;
    public const ROLE_CODE_CUSTOMER = 0;

    private const AVAILABLE_ROLES = [
        self::ROLE_CODE_ADMIN => self::ROLE_ADMIN,
        self::ROLE_CODE_CUSTOMER => self::ROLE_CUSTOMER,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relations

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Scopes

    public function scopeCustomers(Builder $builder)
    {
        return $builder->where('role', self::ROLE_CODE_CUSTOMER);
    }

    // Accessors

    public function getRoleCodeAttribute()
    {
        return $this->getOriginal('role');
    }

    public function getRoleAttribute($value)
    {
        return self::AVAILABLE_ROLES[$value];
    }

    // Mutators

    public function setRoleAttribute(string $role)
    {
        $unified = strtolower($role);

        if (!in_array($unified, self::AVAILABLE_ROLES)) {
            throw new InvalidArgumentException(sprintf('Given user role [%s] is not valid', $role));
        }

        $this->attributes['role'] = array_search($unified, self::AVAILABLE_ROLES);
    }

    public function setRoleCodeAttribute(int $roleCode)
    {
        if (!isset(self::AVAILABLE_ROLES[$roleCode])) {
            throw new InvalidArgumentException(sprintf('Given user role code [%s] is not valid', $roleCode));
        }

        $this->attributes['role'] = $roleCode;
    }
}
