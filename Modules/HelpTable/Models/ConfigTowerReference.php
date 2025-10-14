<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

use Illuminate\Database\Eloquent\Model;

class ConfigTowerReference extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_tower_reference';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];


    // protected $dates = ['deleted_at'];

    public $fillable = [
        'reference',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'reference' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'reference' => 'nullable|string',
        'status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
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
}
