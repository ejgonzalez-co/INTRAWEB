<?php

namespace Modules\ImprovementPlans\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EvaluationHistory
 * @package Modules\ImprovementPlans\Models
 * @version November 15, 2023, 5:21 pm -05
 *
 * @property \Modules\ImprovementPlans\Models\PmEvaluation $pmEvaluations
 * @property \Modules\ImprovementPlans\Models\User $users
 * @property integer $users_id
 * @property integer $pm_evaluations_id
 * @property string $observation
 * @property string $status
 */
class EvaluationHistory extends Model
{
        use SoftDeletes;

    public $table = 'pm_evaluations_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'pm_evaluations_id',
        'user_name',
        'observation',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'users_id' => 'integer',
        'pm_evaluations_id' => 'integer',
        'user_name' => 'string',
        'observation' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'users_id' => 'required',
        'pm_evaluations_id' => 'required',
        'user_name' => 'nullable|string|max:120',
        'observation' => 'nullable|string|max:120',
        'status' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];
    protected $appends = [
        'date_format'
    ];

    public function getDateFormatAttribute() {

        $dateFormat['day'] = $this->created_at->format('d');
        $dateFormat['year'] = $this->created_at->format('Y');
        $dateFormat['hour'] = $this->created_at->format('H:i:s');
        $months = [
            1 => 'ENE',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'ABR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AGO',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DIC',
        ];
    
        $dateFormat['month'] = $months[date('n', strtotime($this->created_at))];

        $months = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];
        $dateFormat['monthcompleto'] = $months[date('n', strtotime($this->created_at))];

        return $dateFormat;

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pmEvaluations() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\PmEvaluation::class, 'pm_evaluations_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\ImprovementPlans\Models\User::class, 'users_id');
    }
}
