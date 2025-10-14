<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaaProcessAttachment
 * @package Modules\ContractualProcess\Models
 * @version September 24, 2021, 9:44 am -05
 *
 * @property Modules\ContractualProcess\Models\PcNeed $pcNeeds
 * @property integer $pc_needs_id
 * @property string $name
 * @property string $attached
 * @property string $description
 */
class PaaProcessAttachment extends Model {
    use SoftDeletes;

    public $table = 'pc_paa_process_attachments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'pc_needs_id',
        'name',
        'attached',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pc_needs_id' => 'integer',
        'name' => 'string',
        'attached' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pc_needs_id' => 'required',
        'name' => 'nullable|string|max:120',
        'description' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcNeeds() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcNeed::class, 'pc_needs_id');
    }
}
