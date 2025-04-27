<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OzonProductExport implements  FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function __construct(protected $products)
    {}

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'Артикул',
            'Название',
            'Цена',
        ];
    }

    public function map($row): array
    {
        return [
            $row->offer_id,
            $row->name,
            $row->price,
        ];
    }
}
