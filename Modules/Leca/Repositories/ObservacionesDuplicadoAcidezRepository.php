<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoAcidez;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoAcidezRepository
 * @package Modules\Leca\Repositories
 * @version July 27, 2022, 3:45 pm -05
*/

class ObservacionesDuplicadoAcidezRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'lc_ensayo_acidez_id',
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
        return ObservacionesDuplicadoAcidez::class;
    }
}
