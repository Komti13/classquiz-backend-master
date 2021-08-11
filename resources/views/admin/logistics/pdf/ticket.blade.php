<!DOCTYPE html>
<html >

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Ticket</title>
</head>
<style>
    * {
        font-family: DejaVu Sans, sans-serif;
        

    }

    p {
        font-size: 1cm;
    }

</style>

<body style="height: 1000px">

    <p>{{ $id }}</p>
    <center style="margin-top: 100px;color: red">
        <div style="width: 100%;color: red;display:inline;">
            <p class="inline" style="display: inline;">{{ $price }} --------------------> {{ $amount }}</p>
        </div>
        <div style="margin-top: 30px"> 
            <p dir="rtl" lang="ar" >{{ $pack }}</p><br>
            <p dir="rtl" lang="ar" >{{ $level }}</p>
            <p>{{ $date }}</p>

        </div>
    </center>

</body>

</html>
