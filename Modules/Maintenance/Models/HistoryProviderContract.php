<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryProviderContract
 * @package Modules\Maintenance\Models
 * @version September 14, 2021, 11:03 am -05
 *
 * @property Modules\Maintenance\Models\User $users
 * @property string $name
 * @property string $value_contract
 * @property string $name_user
 * @property string $observation
 * @property string $cd_avaible
 * @property integer $users_id
 * @property string $object
 * @property string $provider
 */
class HistoryProviderContract extends Model {
    use SoftDeletes;

    public $table = 'mant_history_provider_contract';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'value_contract',
        'name_user',
        'observation',
        'cd_avaible',
        'users_id',
        'object',
        'provider',
        'contract_number',
        'type_contract',
        'condition',
        'dependencias_id',
        'manager_dependencia'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'value_contract' => 'string',
        'name_user' => 'string',
        'observation' => 'string',
        'cd_avaible' => 'string',
        'users_id' => 'integer',
        'object' => 'string',
        'provider' => 'string',
        'type_contract' => 'string',
        'condition' => 'string',
        'dependencias_id' => 'integer',
        'manager_dependencia' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'value_contract' => 'nullable|string|max:255',
        'name_user' => 'nullable|string|max:255',
        'observation' => 'nullable|string',
        'cd_avaible' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'users_id' => 'required',
        'object' => 'nullable|string',
        'provider' => 'nullable|string|max:255'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias() {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

}
