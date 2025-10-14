<?php

namespace Modules\HelpTable\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ConfigTowerHistory
 * @package Modules\HelpTable\Models
 * @version January 21, 2023, 5:57 pm -05
 *
 * @property \Modules\HelpTable\Models\HtTicConfigurationTower $htTicConfigurationTowers
 * @property integer $ht_tic_configuration_towers_id
 * @property integer $user_id
 * @property string $user_name
 * @property string $status
 */
class ConfigTowerHistory extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_configuration_towers_history';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'ht_tic_configuration_towers_id',
        'user_id',
        'user_name',
        'action'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_configuration_towers_id' => 'integer',
        'user_id' => 'integer',
        'user_name' => 'string',
        'action' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_configuration_towers_id' => 'required',
        'user_id' => 'nullable',
        'user_name' => 'nullable|string|max:255',
        'action' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function htTicConfigurationTowers() {
        return $this->belongsTo(\Modules\HelpTable\Models\HtTicConfigurationTower::class, 'ht_tic_configuration_towers_id');
    }
}
