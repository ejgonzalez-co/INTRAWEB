<?php

namespace Modules\Configuracion\Repositories;

use Modules\Configuracion\Models\Variables;
use App\Repositories\BaseRepository;

/**
 * Class VariablesRepository
 * @package Modules\Configuracion\Repositories
 * @version December 22, 2021, 10:33 am -05
*/

class VariablesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'value',
        'type',
        'description'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Variables::class;
    }
}
