<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InternalTypes
 * @package Modules\Correspondence\Models
 * @version January 12, 2022, 2:25 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceInternal2022s
 * @property string $name
 * @property string $template
 * @property string $prefix
 * @property string $variables
 */
class InternalTypes extends Model
{
        use SoftDeletes;

    public $table = 'correspondence_internal_type';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'template',
        'prefix',
        'variables',
        'template_web'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'template' => 'string',
        'prefix' => 'string',
        'variables' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'prefix' => 'nullable|string|max:45',
        'variables' => 'nullable|string'
    ];

    protected $appends = ["variables_documento"];

    /**
     * Variables de una plantilla
     *
     * @return $variables las variables de la plantilla
     */
    public function getVariablesDocumentoAttribute() {
        // Valida si tiene variables el tipo de documento
        if($this->variables) {
            // Formatea las variables de una plantilla separadas por coma (,) retornando un arreglo
            $variables = '[["variable" =>'. str_replace(',', '"],["variable" => "', '"'.$this->variables.'"').']]';
        } else {
            // Si no tiene variables, se crea un arreglo vacío
            $variables = '[]';
        }
        return eval("return ".$variables.";");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function correspondenceInternals() {
        return $this->hasMany(\Modules\Correspondence\Models\Internal::class, 'type');
    }

    // public function setTable($table)
    // {
    //     $this->table = $table;

    //     return $this;
    // }


    // public function __construct($table='correspondence_internal_type_2022')
    // {
    //     $this->table = $table;
    // }

}
