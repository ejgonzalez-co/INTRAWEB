<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TicRequestsDocuments
 * @package Modules\HelpTable\Models
 * @version May 23, 2024, 11:32 am -05
 *
 * @property \Modules\HelpTable\Models\HtTicRequest $htTicRequests
 * @property integer $ht_tic_requests_id
 * @property string $name
 * @property string $description
 * @property string $url
 */
class TicRequestsDocuments extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_requests_documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'ht_tic_requests_id',
        'name',
        'description',
        'url'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_requests_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'url' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'ht_tic_requests_id' => 'required',
        'name' => 'required|string|max:150',
        'description' => 'required|string',
        // 'url' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function TicRequests() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicRequest::class, 'ht_tic_requests_id');
    }
}
