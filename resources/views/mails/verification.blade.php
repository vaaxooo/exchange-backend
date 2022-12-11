<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение почты</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100&display=swap"
        rel="stylesheet">
</head>

<body
    style="height: 100vh;
 display: flex;
  align-items: center;
  justify-content: center;
 background: #fafafa;
 font-family: 'Roboto', sans-serif;
 ">

    <div style="background: #fff;
  width: 30%;
  color: #333;">

        <div style="padding: 30px;">
            <div style="margin-bottom: 20px;font-size: 26px;font-weight: 600;">{{ config('app.name') }}</div>

            <div>Выполнен запрос на подтверждение почты.</div>
            <div>Ваш код подтверждения: {{ $code }}</div>

            <div style="margin-top: 20px;">Если вы не запрашивали подтверждение почты, просто проигнорируйте это
                письмо.</div>

            <div style="margin-top: 30px; font-weight: 500; color: #9e9e9e;">
                {{ date('Y') }} © {{ config('app.name') }}
            </div>

        </div>

    </div>



</body>

</html>
