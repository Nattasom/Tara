<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>@yield('title')</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="all,follow">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Bootstrap CSS-->
<link rel="stylesheet" href="{{Config::get('app.context')}}assets/vendor/bootstrap/css/bootstrap.min.css">
<!-- Font Awesome CSS-->
<link rel="stylesheet" href="{{Config::get('app.context')}}assets/vendor/font-awesome/css/font-awesome.min.css">
<!-- Custom Font Icons CSS-->
<link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/font.css">
<!-- Jquery Confirm -->
<link rel="stylesheet" href="{{Config::get('app.context')}}assets/jquery-confirm/jquery-confirm.min.css">
<!-- Datepicker TH -->
<link rel="stylesheet" href="{{Config::get('app.context')}}assets/datepicker/datepicker.css">
<!-- theme stylesheet-->
<link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/style.default.css" id="theme-stylesheet">
<!-- Custom stylesheet - for your changes-->
<link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/custom.css?v=<?php echo time();?>">
<!-- Favicon-->
<link rel="shortcut icon" href="{{Config::get('app.context')}}assets/img/favicon.ico">
<!-- Tweaks for older IEs--><!--[if lt IE 9]>
    <script src="{{Config::get('app.context')}}assets/html5shiv.min.js"></script>
    <script src="{{Config::get('app.context')}}assets/respond.min.js"></script><![endif]-->