<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigTowerHddCapacity extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_tower_hdd_capacity';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'hdd_capacity',
        'status',
        'marca'
    ];

    protected $casts = [
        'hdd_capacity' => 'string',
        'status' => 'string',
        'marca' => 'string'
    ];

    public static array $rules = [
        'hdd_capacity' => 'required|string',
        'status' => 'required|string|max:255',
        'marca' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
