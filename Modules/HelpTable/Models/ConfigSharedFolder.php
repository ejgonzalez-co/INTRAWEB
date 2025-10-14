<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigSharedFolder extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_tower_shared_folder';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'shared_folder',
        'status'
    ];

    protected $casts = [
        'id'=>'integer',
        'shared_folder' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'shared_folder' => 'required|string',
        'status' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
