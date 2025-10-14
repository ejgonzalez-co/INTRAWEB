<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Maintenance\Models\HistoryContractNews;

/**
 * Class ContractNews
 * @package Modules\Maintenance\Models
 * @version August 30, 2023, 8:06 am -05
 *
 * @property Modules\Maintenance\Models\MantProviderContract $mantProviderContract
 * @property Modules\Maintenance\Models\User $users
 * @property integer $users_id
 * @property integer $mant_provider_contract_id
 * @property string $novelty_type
 * @property integer $number_cdp
 * @property number $contract_modify
 * @property string $consecutive
 * @property string $consecutive_cdp
 * @property string $name_user
 * @property string $date_new
 * @property string $time_extension
 * @property string $date_modification
 * @property string $date_cdp
 * @property string $date_start_suspension
 * @property string $date_end_suspension
 * @property string $date_contract_term
 * @property string $observation
 * @property string $attachment
 * @property number $cdp_modify
 * @property number $value_cdp
 * @property number $value_contract
 * @property number $cdp_available
 */
class ContractNews extends Model {
    use SoftDeletes;

    public $table = 'mant_contract_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'mant_provider_contract_id',
        'novelty_type',
        'number_cdp',
        'contract_modify',
        'consecutive',
        'consecutive_cdp',
        'name_user',
        'date_new',
        'time_extension',
        'date_modification',
        'date_cdp',
        'date_start_suspension',
        'date_end_suspension',
        'date_contract_term',
        'observation',
        'attachment',
        'cdp_modify',
        'value_cdp',
        'value_contract',
        'cdp_available',
        'type_suspension',
        'time_suspension',
        'date_act_suspension',
        'date_last_suspension',
        'date_restart'
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'mant_provider_contract_id' => 'integer',
        'novelty_type' => 'string',
        'number_cdp' => 'integer',
        'contract_modify' => 'float',
        'consecutive' => 'string',
        'consecutive_cdp' => 'string',
        'name_user' => 'string',
        'date_new' => 'date',
        'time_extension' => 'string',
        'date_modification' => 'string',
        'date_cdp' => 'string',
        'date_start_suspension' => 'string',
        'date_end_suspension' => 'string',
        'date_contract_term' => 'string',
        'observation' => 'string',
        'attachment' => 'string',
        'cdp_modify' => 'float',
        'value_cdp' => 'float',
        'value_contract' => 'float',
        'cdp_available' => 'float',
        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function historyNovelty() {
        return $this->hasMany(\Modules\Maintenance\Models\HistoryContractNews::class, 'mant_contract_news_id')->latest();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantProviderContract() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantProviderContract::class, 'mant_provider_contract_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }
}
