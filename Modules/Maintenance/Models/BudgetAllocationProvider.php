<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BudgetAllocationProvider
 * @package Modules\Maintenance\Models
 * @version May 28, 2021, 3:51 pm -05
 *
 * @property Modules\Maintenance\Models\MantProviderContract $mantProviderContract
 * @property string $process
 * @property string $available
 * @property string $executed
 * @property integer $dependencias_id
 * @property integer $mant_provider_contract_id
 */
class BudgetAllocationProvider extends Model
{
    use SoftDeletes;

    public $table = 'mant_budget_allocation_provider';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'process',
        'available',
        'executed',
        'dependencias_id',
        'mant_provider_contract_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'process' => 'string',
        'available' => 'string',
        'executed' => 'string',
        'dependencias_id' => 'integer',
        'mant_provider_contract_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_provider_contract_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantProviderContract()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\MantProviderContract::class, 'mant_provider_contract_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias()
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id')->withTrashed();
    }
}
