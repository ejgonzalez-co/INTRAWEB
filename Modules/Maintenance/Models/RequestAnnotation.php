<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequestAnnotation
 * @package Modules\Maintenance\Models
 * @version January 11, 2024, 2:29 pm -05
 *
 * @property Modules\Maintenance\Models\MantSnRequest $mantSnRequest
 * @property Modules\Maintenance\Models\User $users
 * @property integer $users_id
 * @property integer $mant_sn_request_id
 * @property string $anotacion
 * @property string $name_user
 */
class RequestAnnotation extends Model {
    use SoftDeletes;

    public $table = 'mant_sn_annotations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'mant_sn_request_id',
        'anotacion',
        'name_user'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'mant_sn_request_id' => 'integer',
        'anotacion' => 'string',
        'name_user' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'users_id' => 'required',
        // 'mant_sn_request_id' => 'required',
        // 'anotacion' => 'nullable|string',
        // 'name_user' => 'nullable|string|max:45',
        // 'created_at' => 'nullable',
        // 'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantSnRequest() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantSnRequest::class, 'mant_sn_request_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }
}
