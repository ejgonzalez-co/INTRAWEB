<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigTowerVideoCard extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_tower_video_card';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'video_card',
        'status',
        'marca'
    ];

    protected $casts = [
        'id' => 'integer',
        'video_card' => 'string',
        'status' => 'string',
        'marca' => 'string'
    ];

    public static array $rules = [
        'video_card' => 'required|string',
        'status' => 'required|string|max:255',
        'marca' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
