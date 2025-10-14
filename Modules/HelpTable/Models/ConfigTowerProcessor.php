<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigTowerProcessor extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_tower_processor';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'processor',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'processor' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'processor' => 'required|string',
        'status' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
