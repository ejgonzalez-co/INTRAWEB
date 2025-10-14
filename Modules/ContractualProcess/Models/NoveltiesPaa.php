<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NoveltiesPaa
 * @package Modules\ContractualProcess\Models
 * @version May 20, 2021, 7:28 am -05
 *
 * @property Modules\ContractualProcess\Models\PcNeed $pcNeeds
 * @property integer $users_id
 * @property integer $pc_needs_id
 * @property string $user_name
 * @property string $kind_novelty
 * @property string $observation
 * @property string $attached
 */
class NoveltiesPaa extends Model
{
    use SoftDeletes;

    public $table = 'pc_novelties_paa';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'users_id',
        'pc_needs_id',
        'user_name',
        'kind_novelty',
        'observation',
        'attached'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'pc_needs_id' => 'integer',
        'user_name' => 'string',
        'kind_novelty' => 'string',
        'observation' => 'string',
        'attached' => 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_needs_id' => 'required',
        'kind_novelty' => 'nullable|string|max:45',
        'observation' => 'nullable|string',
        'attached' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcNeeds() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcNeed::class, 'pc_needs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }
}
