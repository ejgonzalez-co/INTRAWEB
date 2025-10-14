<?php

namespace Modules\leca\Repositories;

use Modules\leca\Models\ObservacionesDuplicadoSolidosSecos;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoSolidosSecosRepository
 * @package Modules\leca\Repositories
 * @version December 19, 2022, 5:25 pm -05
*/

class ObservacionesDuplicadoSolidosSecosRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_ensayo_solidos_secos_id',
        'users_id',
        'name_user',
        'observation'
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
        return ObservacionesDuplicadoSolidosSecos::class;
    }
}
