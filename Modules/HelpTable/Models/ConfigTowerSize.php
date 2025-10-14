<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigTowerSize extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_tower_size';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'size',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'size' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'size' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable',
        'status' => 'required|string|max:255'
    ];

    
}
