<!DOCTYPE html>
<html>

<head>
    <title>Успешное оформление заказа</title>
</head>

<body>
    <h1>Ваш заказ успешно оформлен!</h1>
    <p>Номер заказа: {{ $order->id }}</p>
    <p>Спасибо за ваш заказ, {{ $order->first_name }} {{ $order->last_name }}!</p>

    <a href="{{ route('home') }}" class="btn btn-primary">Продолжить покупки</a>
</body>

</html>