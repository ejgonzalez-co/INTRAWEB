<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class City
 * @package Modules\ContractualProcess\Models
 * @version September 23, 2021, 7:55 am -05
 *
 * @property Modules\ContractualProcess\Models\State $state
 * @property Modules\ContractualProcess\Models\Country $country
 * @property \Illuminate\Database\Eloquent\Collection $pcProjectAreaInfluences
 * @property integer $country_id
 * @property integer $state_id
 * @property string $name
 * @property string $state_code
 * @property string $country_code
 * @property number $latitude
 * @property number $longitude
 * @property boolean $flag
 * @property string $wikiDataId
 */
class City extends Model {

    public $table = 'cities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';    
    
    public $fillable = [
        'country_id',
        'state_id',
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
        'country_id' => 'integer',
        'state_id' => 'integer',
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
        'country_id' => 'required|integer',
        'state_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'state_code' => 'required|string|max:255',
        'country_code' => 'required|string|max:2',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'created_at' => 'required',
        'updated_at' => 'required',
        'flag' => 'required|boolean',
        'wikiDataId' => 'nullable|string|max:255'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function state() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\State::class, 'state_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country() {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Country::class, 'country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcProjectAreaInfluences() {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcProjectAreaInfluence::class, 'cities_id');
    }
}
