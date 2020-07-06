<?php use App\Library\PHPDev\CGlobal; ?>
<?php use App\Library\PHPDev\FuncLib; ?>
<?php use Illuminate\Support\Facades\URL; ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <title>LOGIN</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="Login" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="shortcut icon" href="{{FuncLib::getBaseUrl()}}assets/backend/img/favicon.ico" type="image/vnd.microsoft.icon">
    {!!CGlobal::$extraMeta!!}
    <link rel="stylesheet" href="{{URL::asset('assets/backend/ace/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('assets/focus/css/reset.css')}}" />
    {!! CGlobal::$extraHeaderCSS !!}
    {!! CGlobal::$extraHeaderJS !!}
    <script type="text/javascript">var BASE_URL = "{{FuncLib::getBaseUrl()}}";</script>
</head>
<body>
@yield('content')
{!!CGlobal::$extraFooterCSS!!}
{!!CGlobal::$extraFooterJS!!}
</body>
</html>
