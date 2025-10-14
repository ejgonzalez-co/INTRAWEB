<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigOfficeVersion extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_office_version';

    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'office_version',
        'status'
    ];

    protected $casts = [
        'id'=>'integer',
        'office_version' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'office_version' => 'nullable|string',
        'status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
