<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SamplePoints
 * @package Modules\Leca\Models
 * @version November 9, 2021, 9:55 am -05
 *
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcCustomers
 * @property integer $users_id
 * @property string $point_location
 * @property string $no_samples_taken
 * @property string $sector
 * @property string $tank_feeding
 */
class SamplePoints extends Model {
    use SoftDeletes;

    public $table = 'lc_sample_points';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'point_location',
        'no_samples_taken',
        'sector',
        'tank_feeding',
        'code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'point_location' => 'string',
        'no_samples_taken' => 'string',
        'sector' => 'string',
        'tank_feeding' => 'string',
        'code'=> 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'point_location' => 'nullable|string|max:255',
        'no_samples_taken' => 'nullable|string|max:255',
        'sector' => 'nullable|string|max:255',
        'tank_feeding' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lcCustomers() {
        return $this->belongsToMany(\Modules\Leca\Models\Customers::class, 'lc_customers_has_lc_sample_points');
    }
    
}
