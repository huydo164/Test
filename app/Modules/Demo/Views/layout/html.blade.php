<?php
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\FuncLib;
?>
    <!DOCTYPE html>
<html lang="vi">
<head>
    {!! CGlobal::$extraMeta !!}
    <title> @yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="{{FuncLib::getBaseUrl()}}" type="image/vnd.microsoft.icon">
    <link media="all" type="text/css" rel="stylesheet" href="{{URL::asset('assets/libs/bootstrap/css/bootstrap.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/font-awesome/4.5.0/css/font-awesome.min.css')}}" />
    <link media="all" type="text/css" rel="stylesheet" href="{{URL::asset('assets/focus/css/reset.css')}}" />
    <link media="all" type="text/css" rel="stylesheet" href="{{URL::asset('assets/frontend/css/site.css')}}" />
    <link media="all" type="text/css" rel="stylesheet" href="{{URL::asset('assets/frontend/css/media.css')}}" />
    <script src="{{URL::asset('assets/focus/js/jquery.2.1.1.min.js')}}"></script>
    <script src="{{URL::asset('assets/libs/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('assets/libs/number/autoNumeric.js')}}"></script>
    {!! CGlobal::$extraHeaderCSS !!}
    {!! CGlobal::$extraHeaderJS !!}
    <script type="text/javascript">var BASE_URL='{{FuncLib::getBaseUrl()}}';</script>
    @if(CGlobal::is_dev == 0)

    @endif
</head>
<body>
<div id="wrapper">
    @yield('header')
   <div class="">
       @yield('right')
       @yield('content')
   </div>
    @yield('footer')
</div>
{!! CGlobal::$extraFooterCSS !!}
{!! CGlobal::$extraFooterJS !!}
</body>
</html>
