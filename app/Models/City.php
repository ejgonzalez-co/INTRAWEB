<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class City
 * @package App\Models
 * @version January 20, 2022, 3:16 am -05
 *
 * @property \App\Models\State $state
 * @property \App\Models\Country $country
 * @property \Illuminate\Database\Eloquent\Collection $citizens
 * @property integer $state_id
 * @property integer $country_id
 * @property string $name
 * @property string $state_code
 * @property string $country_code
 * @property number $latitude
 * @property number $longitude
 * @property boolean $flag
 * @property string $wikiDataId
 */
class City extends Model
{

    public $table = 'cities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'state_id',
        'country_id',
        'name',
        'state_code',
        'country_code',
        'latitude',
        'longitude',
        'flag',
        'wikiDataId'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'state_id' => 'integer',
        'country_id' => 'integer',
        'name' => 'string',
        'state_code' => 'string',
        'country_code' => 'string',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'flag' => 'boolean',
        'wikiDataId' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'state_id' => 'required|integer',
        'country_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'state_code' => 'required|string|max:255',
        'country_code' => 'required|string|max:2',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'flag' => 'required|boolean',
        'wikiDataId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function state() {
        return $this->belongsTo(\App\Models\State::class, 'state_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country() {
        return $this->belongsTo(\App\Models\Country::class, 'country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function citizens() {
        return $this->hasMany(\App\Models\Citizen::class, 'city_id');
    }
}
