<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PoolImportHistory
 *
 * @property int $id
 * @property string|null $file_name
 * @property int $status
 * @property string $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $job_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PoolImportHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PoolImportHistory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PoolImportHistory whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PoolImportHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PoolImportHistory whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PoolImportHistory whereStatus($value)
 * @mixin \Eloquent
 */
class PoolImportHistory extends Model
{
    const STATUS_SCHEDULED = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_DONE = 3;

    protected $table = 'history';

    public $timestamps = false;

    protected $dates = [
        'created_at',
    ];

    protected $fillable = ['file_name', 'status', 'description', 'timestamp'];

    public function getStatusAttribute($code)
    {
        $statusMap = [
            self::STATUS_SCHEDULED => 'scheduled',
            self::STATUS_IN_PROGRESS => 'in progress',
            self::STATUS_DONE => 'done',
        ];

        return array_key_exists($code, $statusMap) ? $statusMap[$code] : 'Status undefined';
    }
}
