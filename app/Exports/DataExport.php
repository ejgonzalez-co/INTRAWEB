<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

/**
 * Exporta datos a excel o pdf
 *
 * @author Jhoan Sebastian Chilito S. - May. 07 - 2020
 * @version 1.0.0
 */
class DataExport implements FromView {

    /**
     * Exporta los datos de una table de una vista
     *
     * @author Jhoan Sebastian Chilito S. - May. 07 - 2020
     * @version 1.0.0
     */
    public function export($view, $data): View {
        return view($view)
            ->with('data', $data);
    }
}
