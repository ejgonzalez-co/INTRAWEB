<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Intranet\Models\User;

class ExternalCitizen extends Model
{
    public $table = 'correspondence_external_citizens';

    protected $appends = ['ciudadano_datos','ciudad_informacion','departamento_informacion'];

    public $fillable = [
        'correspondence_external_id',
        'citizen_id',
        'citizen_name',
        'citizen_document',
        'citizen_email',
        'department_id',
        'city_id',
        'trato',
        'cargo',
        'entidad',
        'direccion',
        'phone'
    ];

    protected $casts = [
        'citizen_name' => 'string',
        'citizen_document' => 'string',
        'citizen_email' => 'string',
        'phone'  => 'string'
    ];

    public static array $rules = [
        'correspondence_external_id' => 'required',
        'citizen_id' => 'nullable',
        'citizen_name' => 'nullable|string|max:255',
        'citizen_document' => 'nullable|string|max:255',
        'citizen_email' => 'nullable|string|max:255',
        'department_id' => 'nullable',
        'city_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function correspondenceExternal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondenceExternal::class, 'correspondence_external_id');
    }

    public function getCiudadanoDatosAttribute(){
        $citizen = User::where('id',$this->citizen_id)->first();
        return $citizen;
    }

    public function getDepartamentoInformacionAttribute(){
        $department = DB::table('states')->where('id',$this->department_id)->first();
        return $department;
    }

    public function getCiudadInformacionAttribute(){
        $city = DB::table('cities')->where('id',$this->city_id)->first();
        return $city;
    }
}
