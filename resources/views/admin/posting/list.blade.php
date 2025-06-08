@extends('adminlte::page')

@section('content')
    @php
        function sortable($label, $column) {
            $currentSort = request('sort');
            $direction = 'asc';
            $arrowUp = '<span style="color: #ccc;">▲</span>';
            $arrowDown = '<span style="color: #ccc;">▼</span>';

            if ($currentSort) {
                [$currentColumn, $currentDirection] = explode(',', $currentSort);
                if ($currentColumn === $column) {
                    if ($currentDirection === 'asc') {
                        $direction = 'desc';
                        $arrowUp = '<span style="color: #000;">▲</span>';
                    } elseif ($currentDirection === 'desc') {
                        $direction = 'asc';
                        $arrowDown = '<span style="color: #000;">▼</span>';
                    }
                }
            }

            $query = array_merge(request()->except('page'), ['sort' => $column . ',' . $direction]);
            $url = '?' . http_build_query($query);

            return '<a href="' . $url . '" style="text-decoration: none;">' . $label .
                   '<span class="ml-1" style="font-size: 0.8em;">' . $arrowUp . $arrowDown . '</span></a>';
        }
    @endphp
    <div class="card">
        <div class="card-header bg-gradient-primary">
            <h3 class="card-title mt-2">
                <form class="form-inline" id="filterForm">
                    <input type="text" name="search" class="form-control mr-2"
                           value="{{ request('search') }}" placeholder="поиск">

                    <input type="text" id="daterange" class="form-control mr-2" placeholder="Интервал дат">
                    <input type="hidden" name="date_from" id="date_from">
                    <input type="hidden" name="date_to" id="date_to">

                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <button type="submit" class="btn btn-default">Найти</button>
                </form>
            </h3>
            <div class="card-tools" style="float: right">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#exportModal">
                    Выгрузить заказы
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th rowspan="2">{!! sortable('Номер заказа', 'order_number') !!}</th>
                    <th rowspan="2">{!! sortable('id заказа', 'order_id') !!}</th>
                    {{--<th rowspan="2">{!! sortable('Номер отправления', 'posting_number') !!}</th>--}}
                    <th rowspan="2">{!! sortable('Сумма', 'price') !!}</th>
                    <th rowspan="2">{!! sortable('Склад', 'warehouse_id') !!}</th>
                    <th rowspan="2">{!! sortable('Дата', 'created_at') !!}</th>
                    <th colspan="7" class="text-center">Товары</th>
                </tr>
                <tr>
                    <th>Артикул (магазин)</th>
                    <th>Артикул (Ozon)</th>
                    <th>Цена</th>
                    <th>Кол-во</th>
                    <th>Показов</th>
                    <th>Показов в карточке</th>
                    <th>В корзину</th>
                </tr>
                </thead>
                <tbody>
                @foreach($postings as $posting)
                    @foreach($posting->items as $index => $product)
                        <tr>
                            @if($index == 0)
                                <td rowspan="{{ count($posting->items) }}">{{ $posting->order_number }}</td>
                                <td rowspan="{{ count($posting->items) }}">{{ $posting->order_id }}</td>
                                {{--<td rowspan="{{ count($posting->items) }}">{{ $posting->posting_number }}</td>--}}
                                <td rowspan="{{ count($posting->items) }}">{{ number_format($posting->price, 2, '.', ' ') }}</td>
                                <td rowspan="{{ count($posting->items) }}">{{ $posting->warehouse->type->name }}</td>
                                <td rowspan="{{ count($posting->items) }}">{{ ($posting->created_at)->format('H:i d.m.Y') }}</td>
                            @endif
                                <td>{{ $product->product->offer_id ?? '—' }}</td>
                                <td>{{ $product->product->sku ?? '—' }}</td>
                                <td>{{ number_format($product->price, 2, '.', ' ') }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->product->getHitsView() ?? '—' }}</td>
                                <td>{{ $product->product->getHitsViewPdp() ?? '—' }}</td>
                                <td>{{ $product->product->getHitsViewToCart() ?? '—' }}</td>
                        </tr>
                    @endforeach
                @endforeach

                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <ul class="pagination pagination-sm m-0 float-right">
                {{ $postings->appends(request()->except('page'))->links() }}
            </ul>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('ozon.posting.export') }}" method="GET"> {{-- Укажи свой маршрут --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportModalLabel">Выгрузить заказы</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="date_from">Дата от:</label>
                            <input type="date" class="form-control" name="date_from" required>
                        </div>
                        <div class="form-group">
                            <label for="date_to">Дата до:</label>
                            <input type="date" class="form-control" name="date_to" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Выгрузить</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#daterange').daterangepicker({
                autoUpdateInput: true,
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'Применить',
                    cancelLabel: 'Очистить',
                    daysOfWeek: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                    monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                        'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                    firstDay: 1
                }
            }, function(start, end) {
                document.getElementById('date_from').value = start.format('YYYY-MM-DD');
                document.getElementById('date_to').value = end.format('YYYY-MM-DD');
                document.getElementById('filterForm').submit();
            });

            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#date_from').val('');
                $('#date_to').val('');
                document.getElementById('filterForm').submit();
            });
        });
    </script>
@endsection
