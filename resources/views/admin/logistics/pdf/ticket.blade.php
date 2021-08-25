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
    @foreach ($pages as $page)
    <div style="height: 1000px;page-break-after: always;page-break-inside: avoid">
        <p>{{ $page['id'] }}</p>
        <center style="margin-top: 100px;color: red">
            <div style="width: 100%;color: red;display:inline;">
                <p class="inline" style="display: inline;">{{ $page['price'] }} --------------------> {{ $page['amount']  }}</p>
            </div>
            <div style="margin-top: 30px"> 
                <p dir="rtl" lang="ar" >{{ $page['pack']  }}</p><br>
                <p dir="rtl" lang="ar" >{{ $page['level']  }}</p>
                <p>{{ $page['date']  }}</p>
    
            </div>
        </center>
    </div>
@endforeach
   

</body>

</html>
