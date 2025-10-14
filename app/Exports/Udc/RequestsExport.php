<?php

namespace App\Exports\Udc;

use Modules\UpdateCitizenData\Models\UdcRequest;
use Maatwebsite\Excel\Concerns\FromCollection;

class RequestsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return UdcRequest::all();
    }
}
