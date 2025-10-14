<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TypesActivity
 * @package Modules\Maintenance\Models
 * @version March 12, 2021, 9:39 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $mantProviders
 * @property string $name
 * @property string $description
 */
class TypesActivity extends Model
{
    use SoftDeletes;

    public $table = 'mant_types_activity';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantProviders()
    {
        return $this->hasMany(\Modules\Maintenance\Models\MantProvider::class, 'mant_types_activity_id');
    }
}
