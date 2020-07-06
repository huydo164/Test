<?php use App\Library\PHPDev\CGlobal; ?>
<?php use App\Library\PHPDev\FuncLib; ?>
<?php use Illuminate\Support\Facades\URL; ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <title>ADMIN</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    {!!CGlobal::$extraMeta!!}
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/font-awesome/4.5.0/css/font-awesome.min.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/css/fonts.googleapis.com.css')}}" />
    <!-- ace styles -->
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/css/ace.min.css')}}" />
<!--[if lte IE 9]>
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/css/ace-part2.min.css')}}" />
    <![endif]-->
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/css/ace-skins.min.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/css/ace-rtl.min.css')}}" />
<!--[if lte IE 9]>
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/css/ace-ie.min.css')}}" />
    <![endif]-->
    <!--[if !IE]> -->
    <script src="{{URL::asset('assets/backend/ace/js/jquery-2.1.4.min.js')}}"></script>
    <!-- <![endif]-->
<!--[if IE]>
    <script src="{{URL::asset('assets/backend/ace/js/jquery-1.11.3.min.js')}}"></script>
    <![endif]-->
    <script src="{{URL::asset('assets/backend/ace/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('assets/backend/ace/js/ace-extra.min.js')}}"></script>
    <script src="{{URL::asset('assets/backend/ace/js/ace-elements.min.js')}}"></script>
    <script src="{{URL::asset('assets/backend/ace/js/ace.min.js')}}"></script>
<!--[if lte IE 8]>
    <script src="{{URL::asset('assets/backend/ace/js/html5shiv.min.js')}}"></script>
    <script src="{{URL::asset('assets/backend/ace/js/respond.min.js')}}"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{URL::asset('assets/focus/css/reset.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('assets/backend/css/admin.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('assets/backend/css/media.css')}}" />
    {!! CGlobal::$extraHeaderCSS !!}
    {!! CGlobal::$extraHeaderJS !!}
    <script type="text/javascript">var BASE_URL = "{{FuncLib::getBaseUrl()}}";</script>
</head>
<body class="no-skin">
@yield('header')
<div class="main-container " id="main-container">
    <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
    </script>
    @yield('left')
    @yield('content')
</div>
@yield('footer')
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='{{URL::asset('assets/backend/ace/js/jquery.mobile.custom.min.js')}}'>"+"<"+"/script>");
</script>
{!!CGlobal::$extraFooterCSS!!}
{!!CGlobal::$extraFooterJS!!}
</body>
</html>
