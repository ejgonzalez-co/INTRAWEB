<?php

namespace Modules\leca\Repositories;

use Modules\leca\Models\ObservacionesEspectro;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesEspectroRepository
 * @package Modules\leca\Repositories
 * @version August 31, 2022, 3:55 pm -05
*/

class ObservacionesEspectroRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'ensayo',
        'name_user',
        'observation',
        'lc_ensayo_espectro_id'
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
        return ObservacionesEspectro::class;
    }
}
