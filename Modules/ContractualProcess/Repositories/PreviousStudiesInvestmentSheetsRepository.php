<?php

namespace Modules\ContractualProcess\Repositories;

use Modules\ContractualProcess\Models\PreviousStudiesInvestmentSheets;
use App\Repositories\BaseRepository;

/**
 * Class PreviousStudiesInvestmentSheetsRepository
 * @package Modules\ContractualProcess\Repositories
 * @version July 1, 2021, 10:53 am -05
*/

class PreviousStudiesInvestmentSheetsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pc_investment_technical_sheets_id'
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
        return PreviousStudiesInvestmentSheets::class;
    }
}
