<?php

namespace Modules\leca\Repositories;

use Modules\leca\Models\ObservacionesDuplicadoSolidosDis;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoSolidosDisRepository
 * @package Modules\leca\Repositories
 * @version December 19, 2022, 11:30 am -05
*/

class ObservacionesDuplicadoSolidosDisRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_ensayo_solidos_dis_id',
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
        return ObservacionesDuplicadoSolidosDis::class;
    }
}
