<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class License
 * @package Modules\Intranet\Models
 * @version June 15, 2021, 6:57 pm -05
 *
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property integer $checked_out
 * @property string|\Carbon\Carbon $checked_out_time
 * @property boolean $published
 * @property integer $ordering
 */
class License extends Model
{
        use SoftDeletes;

    public $table = 'intranet_downloads_licenses';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'title',
        'alias',
        'description',
        'checked_out',
        'checked_out_time',
        'published',
        'ordering'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'alias' => 'string',
        'description' => 'string',
        'checked_out' => 'integer',
        'checked_out_time' => 'datetime',
        'ordering' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usuario()
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'checked_out');
    }

    
}
