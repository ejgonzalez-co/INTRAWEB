<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoNitritos;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoNitritosRepository
 * @package Modules\Leca\Repositories
 * @version June 16, 2022, 5:44 pm -05
*/

class ObservacionesDuplicadoNitritosRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_nitritos_id'
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
        return ObservacionesDuplicadoNitritos::class;
    }
}
