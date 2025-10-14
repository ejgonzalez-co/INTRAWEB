<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cities
 * @package Modules\ContractualProcess\Models
 * @version January 15, 2021, 3:11 pm -05
 *
 * @property Modules\ContractualProcess\Models\Department $department
 * @property \Illuminate\Database\Eloquent\Collection $pcProjectAreaInfluences
 * @property integer $state_id
 * @property string $name
 */
class Cities extends Model
{

    public $table = 'cities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';





    public $fillable = [
        'state_id',
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'state_id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'state_id' => 'required',
        'name' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function department()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Department::class, 'state_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function projectAreaInfluences()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcProjectAreaInfluence::class, 'cities_id');
    }
}
