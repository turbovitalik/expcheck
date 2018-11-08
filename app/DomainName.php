<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DomainName
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $source
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DomainName whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DomainName whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DomainName whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DomainName whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DomainName whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DomainName whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DomainName extends Model
{
    const SOURCE_POOL = 1;
    const SOURCE_EDNET = 2;

    /**
     * @var string
     */
    protected $table = 'domains';

    /**
     * @var array
     */
    protected $fillable = ['name', 'created_at', 'expires_at', 'updated_at', 'trust_flow', 'citation_flow', 'tld'];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $dates = [
        'expires_at',
        'created_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
