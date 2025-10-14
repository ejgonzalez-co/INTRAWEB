<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigTowerMemoryRam extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_tower_memory_ram';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'memory_ram',
        'status'
    ];

    protected $casts = [
        'memory_ram' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'memory_ram' => 'required|string',
        'status' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
