<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ListTrials;
use App\Repositories\BaseRepository;

/**
 * Class ListTrialsRepository
 * @package Modules\Leca\Repositories
 * @version February 11, 2022, 10:02 am -05
*/

class ListTrialsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'number_list',
        'code',
        'name',
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
        return ListTrials::class;
    }
}
