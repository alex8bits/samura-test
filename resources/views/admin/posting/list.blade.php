@extends('adminlte::page')

@section('content')
    <div class="card">
        <div class="card-header bg-gradient-primary">
            <h3 class="card-title mt-2">
                <form class="form-inline">
                    <input type="text" name="search" class="form-control mr-1" value="{{ request('search') }}"
                           placeholder="поиск">
                    <input type="submit" class="form-control" value="Найти">
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
                <th>Номер заказа</th>
                <th>id заказа</th>
                <th>Номер отправления</th>
                <th>Товары</th>
                <th>Сумма</th>
                <th>Склад</th>
                <th>Дата</th>
                </thead>
                <tbody>
                @foreach($postings as $posting)
                    <tr>
                        <td>{{ $posting->order_number }}</td>
                        <td>{{ $posting->order_id }}</td>
                        <td>{{ $posting->posting_number }}</td>
                        <td><ul class="list-unstyled">
                                @foreach($posting->items as $product)
                                    <li>
                                        <strong>Артикул в магазине:</strong> {{ $product->offer_id }}<br>
                                        <strong>Артикул в Ozon:</strong> {{ $product->sku }}<br>
                                        <strong>Цена:</strong> {{ number_format($product->price, 2, '.', ' ') }}<br>
                                        <strong>Количество:</strong> {{ $product->quantity }}
                                    </li>
                                @endforeach
                            </ul></td>
                        <td>{{ number_format($posting->total(), 2, '.', ' ') }}</td>
                        <td>{{ $posting->warehouse->type->name }}</td>
                        <td>{{ ($posting->created_at)->format('H:i d.m.Y') }}</td>
                    </tr>
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
