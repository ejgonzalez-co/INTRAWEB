<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class State
 * @package App\Models
 * @version January 20, 2022, 3:13 am -05
 *
 * @property \App\Models\Country $country
 * @property \Illuminate\Database\Eloquent\Collection $cities
 * @property integer $country_id
 * @property string $name
 * @property string $country_code
 * @property string $fips_code
 * @property string $iso2
 * @property number $latitude
 * @property number $longitude
 * @property boolean $flag
 * @property string $wikiDataId
 */
class State extends Model
{

    public $table = 'states';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'country_id',
        'name',
        'country_code',
        'fips_code',
        'iso2',
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
        'country_id' => 'integer',
        'name' => 'string',
        'country_code' => 'string',
        'fips_code' => 'string',
        'iso2' => 'string',
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
        'country_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'country_code' => 'required|string|max:2',
        'fips_code' => 'nullable|string|max:255',
        'iso2' => 'nullable|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'flag' => 'required|boolean',
        'wikiDataId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country() {
        return $this->belongsTo(\App\Models\Country::class, 'country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cities() {
        return $this->hasMany(\App\Models\City::class, 'state_id');
    }
}
