<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Оплата была отменена №{{ $model->getId() }}</title>
    <script>
        window.close();
        localStorage.setItem('payment-failed', {{ $model->getId() }});
    </script>
</head>
<body>
<h1>Оплата была отменена №{{ $model->getId() }}</h1>
</body>
</html>
