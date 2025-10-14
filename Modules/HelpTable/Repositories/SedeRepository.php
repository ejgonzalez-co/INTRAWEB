<?php

namespace Modules\HelpTable\Repositories;

use App\Repositories\BaseRepository;
use Modules\HelpTable\Models\SedeTicRequest;

/**
 * Class SedeRepository
 * @package Modules\HelpTable\Repositories
 * @version November 13, 2024, 4:55 pm -05
*/

class SedeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'estado',
        'name'
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
        return SedeTicRequest::class;
    }
}
