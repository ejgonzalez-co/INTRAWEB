<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SentTypes
 * @package Modules\Correspondence\Models
 * @version January 12, 2022, 2:25 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceSent2022s
 * @property string $name
 * @property string $template
 * @property string $prefix
 * @property string $variables
 */
class SentTypes extends Model
{
    use SoftDeletes;

    public $table = 'correspondence_sent_type';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'template',
        'prefix',
        'variables'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'template' => 'string',
        'prefix' => 'string',
        'variables' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'template' => 'nullable|string',
        'prefix' => 'nullable|string|max:45',
        'variables' => 'nullable|string'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function correspondenceSents() {
        return $this->hasMany(\Modules\Correspondence\Models\Sent::class, 'type');
    }

    // public function setTable($table)
    // {
    //     $this->table = $table;
 
    //     return $this;
    // }


    // public function __construct($table='correspondence_internal_type_2022')
    // {
    //     $this->table = $table;
    // }
    
}
