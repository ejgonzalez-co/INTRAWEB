<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CriticalEquipment
 * @package Modules\Leca\Models
 * @version February 14, 2022, 5:24 pm -05
 *
 * @property Modules\Leca\Models\LcNitrito $lcNitritos
 * @property integer $lc_list_trials_id
 * @property string $equipo_critico
 * @property string $identificacion
 */
class CriticalEquipment extends Model {
    use SoftDeletes;

    public $table = 'lc_critical_equipment';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'lc_list_trials_id',
        'equipo_critico',
        'identificacion',
        'patron',
        'name_trials',
        'decimales'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_list_trials_id' => 'integer',
        'equipo_critico' => 'string',
        'identificacion' => 'string',
        'patron' => 'string',
        'name_trials' => 'string',
        'decimales' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lc_list_trials_id' => 'required',
        'name_trials' => 'nullable|string|max:255',
        'equipo_critico' => 'nullable|string|max:255',
        'identificacion' => 'nullable|string|max:255',
        'patron' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcListtrials() {
        return $this->belongsTo(\Modules\Leca\Models\ListTrials::class, 'lc_list_trials_id');
    }
}
