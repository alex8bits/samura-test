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
                <a href="#" class="btn btn-default">Выгрузить заказы</a>
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
@endsection
