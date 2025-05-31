<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OzonPostingExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    private $flatItems;

    public function __construct(private $postings)
    {
        $this->flatItems = $this->postings->flatMap(function ($posting) {
            return $posting->items->map(function ($product) use ($posting) {
                $product->parent_posting = $posting;
                return $product;
            });
        });
    }

    public function collection()
    {
        return $this->flatItems;
    }

    public function headings(): array
    {
        return [
            'ID заказа',
            'Номер заказа',
            'Номер отправления',
            'Сумма',
            'Склад',
            'Дата',
            'Артикул магазина',
            'Артикул Ozon',
            'Название',
            'Цена',
            'Количество',
            'Показов (всего)',
            'Показов в карточке',
            'В корзину (всего)',
        ];
    }

    public function map($row): array
    {
        $posting = $row->parent_posting;

        return [
            $posting->order_id,
            $posting->order_number,
            $posting->posting_number,
            $posting->total(),
            $posting->warehouse->type->name ?? '',
            $posting->created_at->format('H:i d.m.Y'),

            $row->offer_id,
            $row->sku,
            $row->name,
            number_format($row->price, 2, '.', ''),
            $row->quantity,

            optional($row->getProduct())->getHitsView() ?? 0,
            optional($row->getProduct())->getHitsViewPdp() ?? 0,
            optional($row->getProduct())->getHitsViewToCart() ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        $sheet->getStyle('A:N')->getAlignment()->setWrapText(true);

        return [];
    }
}
