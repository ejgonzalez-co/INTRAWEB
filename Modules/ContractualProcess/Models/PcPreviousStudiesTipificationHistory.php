<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PcPreviousStudiesTipificationHistory
 * @package Modules\ContractualProcess\Models
 * @version March 10, 2021, 4:32 pm -05
 *
 * @property Modules\ContractualProcess\Models\PcPreviousStudiesH $pcPreviousStudiesH
 * @property string $type_danger
 * @property string $danger
 * @property string $effect
 * @property boolean $probability
 * @property boolean $impact
 * @property string $allocation_danger
 * @property integer $pc_previous_studies_h_id
 */
class PcPreviousStudiesTipificationHistory extends Model
{
    use SoftDeletes;

    public $table = 'pc_previous_studies_tipification_h';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'type_danger',
        'danger',
        'effect',
        'probability',
        'impact',
        'allocation_danger',
        'pc_previous_studies_h_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type_danger' => 'string',
        'danger' => 'string',
        'effect' => 'string',
        'probability' => 'integer',
        'impact' => 'integer',
        'allocation_danger' => 'string',
        'pc_previous_studies_h_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_previous_studies_h_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcPreviousStudiesH()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPreviousStudiesH::class, 'pc_previous_studies_h_id');
    }
}
