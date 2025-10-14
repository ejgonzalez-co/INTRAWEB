<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PcPreviousStudiesTipification
 * @package Modules\ContractualProcess\Models
 * @version January 9, 2021, 11:48 am -05
 *
 * @property Modules\ContractualProcess\Models\PcPreviousStudy $pcPreviousStudies
 * @property string $type_danger
 * @property string $danger
 * @property string $effect
 * @property boolean $probability
 * @property boolean $impact
 * @property string $allocation_danger
 * @property integer $pc_previous_studies_id
 */
class PcPreviousStudiesTipification extends Model
{
    //use SoftDeletes;

    public $table = 'pc_previous_studies_tipification';
    
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
        'pc_previous_studies_id'
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
        'pc_previous_studies_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_previous_studies_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcPreviousStudies()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPreviousStudy::class, 'pc_previous_studies_id');
    }
}
