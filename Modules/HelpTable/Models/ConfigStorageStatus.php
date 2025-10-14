<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigStorageStatus extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_storage_status';

    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'storage_status',
        'status'
    ];

    protected $casts = [
        'id'=>'integer',
        'storage_status' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'storage_status' => 'nullable|string',
        'status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
