<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoNitratos;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoNitratosRepository
 * @package Modules\Leca\Repositories
 * @version June 16, 2022, 5:47 pm -05
*/

class ObservacionesDuplicadoNitratosRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_nitratos_id'
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
        return ObservacionesDuplicadoNitratos::class;
    }
}
