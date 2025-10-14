<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DependenciaTicRequest
 * @package Modules\HelpTable\Models
 * @version October 15, 2021, 12:03 pm -05
 *
 * @property \Modules\HelpTable\Models\HtSedesTicRequest $htSedesTicRequest
 * @property \Illuminate\Database\Eloquent\Collection $htTicRequests
 * @property string $name
 * @property integer $ht_sedes_tic_request_id
 */
class DependenciaTicRequest extends Model
{
        use SoftDeletes;

    public $table = 'ht_dependencias_tic_request';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'ht_sedes_tic_request_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'ht_sedes_tic_request_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ht_sedes_tic_request_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function htSedesTicRequest() {
        return $this->belongsTo(\Modules\HelpTable\Models\HtSedesTicRequest::class, 'ht_sedes_tic_request_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function htTicRequests() {
        return $this->hasMany(\Modules\HelpTable\Models\HtTicRequest::class, 'ht_dependencias_tic_request_id');
    }
}
