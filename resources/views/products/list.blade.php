<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблица товаров</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3">

<div class="container mt-5">
    <h1 class="mb-4">Список товаров</h1>

    <a href="{{ route('ozon.product.export') }}" class="btn btn-primary mb-3">Выгрузить товары из магазина Самура</a>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">Артикул</th>
            <th scope="col">Название</th>
            <th scope="col">Цена</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->offer_id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }} ₽</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
