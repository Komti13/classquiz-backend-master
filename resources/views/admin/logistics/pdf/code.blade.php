<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Codes</title>
</head>


<body>
    @foreach ($pages as $page) 
         <div  style="height: 1000px;page-break-after: always;page-break-inside: avoid">
           <p>{{ $page['id'] }} </p>
            <h1 style="margin-top: 500px;font-size: 100px;margin-left: 50px">{{ $page['token'] }}</h1>
        </div> 
    @endforeach

</body>

</html>
