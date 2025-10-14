<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigTowerSsdCapacity extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_tower_ssd_capacity';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'ssd_capacity',
        'status',
        'marca'
    ];

    protected $casts = [
        'id' => 'integer',
        'ssd_capacity' => 'string',
        'status' => 'string',
        'marca' => 'string'
    ];

    public static array $rules = [
        'ssd_capacity' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable',
        'status' => 'required|string|max:255',
        'marca' => 'required|string'
    ];

    
}
