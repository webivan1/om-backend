<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Оплата прошла успешно №{{ $model->getId() }}</title>
    <script>
        window.close();
        localStorage.setItem('payment-success', {{ $model->getId() }});
    </script>
</head>
<body>
    <h1>Оплата прошла успешно №{{ $model->getId() }}</h1>
</body>
</html>
