<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SamplingSchedule
 * @package Modules\Leca\Models
 * @version November 24, 2021, 5:32 pm -05
 *
 * @property Modules\Leca\Models\LcOfficial $lcOfficials
 * @property Modules\Leca\Models\LcSamplePoint $lcSamplePoints
 * @property Modules\Leca\Models\User $users
 * @property integer $lc_sample_points_id
 * @property integer $lc_officials_id
 * @property integer $users_id
 * @property string $sampling_date
 * @property string $direction
 * @property string $observation
 */
class SamplingSchedule extends Model
{
    use SoftDeletes;

    public $table = 'lc_sampling_schedule';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_sample_points_id',
        // 'lc_officials_id',
        'users_id',
        'sampling_date',
        'direction',
        'observation',
        'users_name',
        'duplicado',
        'user_creador',
        'fisico',
        'quimico',
        'microbiologico',
        'todos',
        'mensaje',
        'vigencia',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_sample_points_id' => 'integer',
        // 'lc_officials_id' => 'integer',
        'users_id' => 'integer',
        'sampling_date' => 'string',
        'users_name',
        'duplicado' => 'string',
        // 'direction' => 'string',
        // 'observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_sample_points_id' => 'required',
        // 'lc_officials_id' => 'required',
        'sampling_date' => 'nullable|string|max:255',
        'direction' => 'nullable|string|max:255',
        'duplicado' => 'nullable|string|max:255',
        'observation' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    protected $appends = [
        'nombre_punto',
    ];

    public function getNombrePuntoAttribute()
    {
        $toma = $this->lcSamplePoints;
        return $toma != null ? $toma->point_location : '';
    }
    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //  **/
    // public function lcOfficials() {
    //     return $this->belongsTo(\Modules\Leca\Models\LcOfficial::class, 'lc_officials_id');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSamplePoints()
    {
        return $this->belongsTo(\Modules\Leca\Models\SamplePoints::class, 'lc_sample_points_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tomasMuestra()
    {
        return $this->hasMany(\Modules\Leca\Models\SampleTaking::class, 'lc_sampling_schedule_id')->latest();
    }
}
