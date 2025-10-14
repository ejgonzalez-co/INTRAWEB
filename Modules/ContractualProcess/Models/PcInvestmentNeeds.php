<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PcInvestmentNeeds
 * @package Modules\ContractualProcess\Models
 * @version December 4, 2020, 5:44 pm -05
 *
 * @property Modules\ContractualProcess\Models\PcNeed $needs
 * @property string $description
 * @property number $estimated_value
 * @property string $observation
 * @property integer $pc_needs_id
 */
class PcInvestmentNeeds extends Model
{
    use SoftDeletes;

    public $table = 'pc_investment_needs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'description',
        'estimated_value',
        'observation',
        'pc_needs_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'description' => 'string',
        'estimated_value' => 'float',
        'observation' => 'string',
        'pc_needs_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_needs_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function needs()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcNeed::class, 'pc_needs_id');
    }
}
