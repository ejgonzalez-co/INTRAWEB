<?php

namespace Modules\HelpTable\Models;
use DateTimeInterface;

use Illuminate\Database\Eloquent\Model;

class TicTypeTicCategoryHistory extends Model
{
    public $table = 'ht_tic_type_tic_categories_history';

    public $fillable = [
        'name',
        'id_categories',
        'tipos'
    ];

    protected $casts = [
        'name' => 'string',
        'tipos' => 'string'

    ];

    public static array $rules = [
        'name' => 'required|string|max:80',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable',
        'id_categories' => 'required'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function idCategories(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\HelpTable\Models\HtTicTypeTicCategory::class, 'id_categories');
    }
}
