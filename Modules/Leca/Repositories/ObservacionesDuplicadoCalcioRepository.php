<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoCalcio;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoCalcioRepository
 * @package Modules\Leca\Repositories
 * @version August 1, 2022, 4:58 pm -05
*/

class ObservacionesDuplicadoCalcioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_calcio_id'
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
        return ObservacionesDuplicadoCalcio::class;
    }
}
