<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryContractNews
 * @package Modules\Maintenance\Models
 * @version August 31, 2023, 8:41 am -05
 *
 * @property Modules\Maintenance\Models\MantContractNews $mantContractNews
 * @property Modules\Maintenance\Models\User $users
 * @property integer $users_id
 * @property integer $mant_contract_news_id
 * @property string $user_name
 * @property string $novelty_type
 * @property string $description
 * @property string $date_previus_contract_term
 * @property string $date_contract_term
 */
class HistoryContractNews extends Model {
    use SoftDeletes;
    
    public $table = 'mant_hystory_contract_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'mant_contract_news_id',
        'user_name',
        'novelty_type',
        'description',
        'date_previus_contract_term',
        'date_contract_term'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'mant_contract_news_id' => 'integer',
        'user_name' => 'string',
        'novelty_type' => 'string',
        'description' => 'string',
        'date_previus_contract_term' => 'string',
        'date_contract_term' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'mant_contract_news_id' => 'required',
        'user_name' => 'nullable|string|max:45',
        'novelty_type' => 'nullable|string|max:45',
        'description' => 'nullable|string',
        'date_previus_contract_term' => 'nullable|string|max:45',
        'date_contract_term' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantContractNews() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantContractNews::class, 'mant_contract_news_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }
}
