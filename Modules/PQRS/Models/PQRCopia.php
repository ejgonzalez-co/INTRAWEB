<?php

namespace Modules\PQRS\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class PQRCopia extends Model
{
    public $table = 'pqr_copia';

    public $fillable = [
        'vigencia',
        'pqr_id',
        'users_id',
        'tipo',
        'name'
    ];

    protected $casts = [
        'name' => 'string'
    ];

    protected $appends = ['fullname'];

    public static array $rules = [
        'vigencia' => 'nullable',
        'pqr_id' => 'required',
        'users_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function pqr(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\PQR::class, 'pqr_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function getFullnameAttribute()
    {
        // Combinar el nombre, cargo y dependencia en el nombre completo.
        return "{$this->name}";
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
