<html>
    <head>
        <title>Print</title>

        <link rel="stylesheet" href="{{asset('printing/css/printing.css')}}">
        <link rel="stylesheet" href="{{asset('vendor/laravel-admin/font-awesome/css/font-awesome.min.css')}}">

    </head>
    <body>

        <img  src="{{asset('printing/images/report_voucher_header.png')}}" alt="">

        @yield('content')

    </body>
</html>