<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoCarbonoOrganico;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoCarbonoOrganicoRepository
 * @package Modules\Leca\Repositories
 * @version June 16, 2022, 5:49 pm -05
*/

class ObservacionesDuplicadoCarbonoOrganicoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_carbono_organico_id'
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
        return ObservacionesDuplicadoCarbonoOrganico::class;
    }
}
