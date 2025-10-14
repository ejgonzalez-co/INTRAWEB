<?php

namespace Modules\HelpTable\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConfigNetworkCard extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_tower_network_card';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];
    public $fillable = [
        'network_card',
        'status'
    ];

    protected $casts = [
        'id'=>'integer',
        'network_card' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'network_card' => 'required|string',
        'status' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];
}
