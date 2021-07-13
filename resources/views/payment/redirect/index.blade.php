<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="{{asset('/payment/css/redirect.css')}}">
    <title>ClassQuiz</title>
</head>
<body>
    <h1>Veuillez patienter...</h1>
    <div id="loading"></div>
    <form name="gpgForm" method="POST" action="{{$gpgUrl}}">
        @foreach($data as $name => $value)
            <input type="hidden" name="{{$name}}" value="{{$value}}">
        @endforeach
    </form>
    <script>
        document.gpgForm.submit();
    </script>
</body>
</html>
