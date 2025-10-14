<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigUnnecessaryApps extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_unnecessary_apps';

    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'unnecessary_app',
        'status'
    ];

    protected $casts = [
        'id'=>'integer',
        'unnecessary_app' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'unnecessary_app' => 'nullable|string',
        'status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
