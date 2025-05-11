<?php

namespace App\Exports;

use App\Models\OzonPosting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OzonPostingExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    public function __construct(private $postings)
    {

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->postings;
    }

    public function headings(): array
    {
        return [
            'id заказа',
            'Номер заказа',
            'Номер отправления',
            'Сумма',
            'Склад',
            'Дата',
            'Товары',
        ];
    }

    public function map($row): array
    {
        $productList = $row->items?->map(function ($product) {
            return sprintf(
                'Артикул: %s, Артикул Ozon: %s, Название: %s, Цена: %.2f, Кол-во: %d',
                $product->offer_id,
                $product->sku,
                $product->name,
                $product->price,
                $product->quantity
            );
        })->implode(chr(10));

        return [
            $row->order_id,
            $row->order_number,
            $row->posting_number,
            $row->total(),
            $row->warehouse->type->name,
            $row->created_at->format('H:i d.m.Y'),
            $productList,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('D')->getAlignment()->setWrapText(true);

        return [];
    }
}
