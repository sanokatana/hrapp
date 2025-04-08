<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KaryawanExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Database' => new DatabaseSheet(),
            'Family' => new FamilySheet(),
            'Resign' => new ResignSheet()
        ];
    }
}
