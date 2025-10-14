<?php

namespace Modules\PQRS\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PQRAnotacion extends Model
{
    public $table = 'pqr_anotacion';

    public $fillable = [
        'nombre_usuario',
        'anotacion',
        'vigencia',
        'pqr_id',
        'users_id',
        'leido_por',
        'attached'

    ];

    protected $casts = [
        'nombre_usuario' => 'string',
        'anotacion' => 'string'
    ];

    public static array $rules = [
        'nombre_usuario' => 'nullable|string|max:255',
        'anotacion' => 'nullable|string',
        'vigencia' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ['leido_anotacion','date_format'];

    /**
     * Append para validar si el usuario que esta en sesión ya ha leído la anotación
     *
     * @return void
     */
    public function getLeidoAnotacionAttribute() {
        // Retorna solo el registro de leido del usuario en sesión de cada anotación
        $leidoAnotacion = $this->pqrAnotacionLeidos->filter(function($value, $key) {
            return $value["users_id"] == (Auth::user() ? Auth::user()->id : null);
        })->first();
        // Retorna el valor de la propiedad destacado del registro obtenido anteriormente
        return $leidoAnotacion ? $leidoAnotacion->accesos : null; 
    }

        /**
     * Append
     *
     * @return void
     */
    public function getDateFormatAttribute() {

        $dateFormat['day'] = $this->created_at->format('d');
        $dateFormat['year'] = $this->created_at->format('Y');
        $dateFormat['hour'] = $this->created_at->format('H:i:s');
        $months = [
            1 => 'ENE',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'ABR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AGO',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DIC',
        ];
    
        $dateFormat['month'] = $months[date('n', strtotime($this->created_at))];

        $months = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];
        $dateFormat['monthcompleto'] = $months[date('n', strtotime($this->created_at))];

        return $dateFormat;

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

    public function pqr(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\PQR::class, 'pqr_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function pqrAnotacionLeidos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\PQRLeidoAnotacion::class, 'pqr_anotacion_id');
    }
}
