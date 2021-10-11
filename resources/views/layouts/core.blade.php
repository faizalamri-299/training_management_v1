<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Training Management System</title>

<link rel="icon" type="image/svg+xml" href="images/favicon.svg">
      <link rel="mask-icon" color="#5bbad5" href="images/favicon.svg">


    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/onsenui.min.css') }}" />
 <link rel="stylesheet" type="text/css" href="{{ asset('css/onsen-css-components.min.css') }}" /> 
    <!--   <link rel="stylesheet" type="text/css" href="{{ asset('css/dark-onsen-css-components.min.css') }}" />-->
      
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mystyle.css') }}" />
      

    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
      <!--
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/myapp.css') }}"/>
-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
 
  </head>
  <body>
      @yield('body')
  
    <script src="{{ asset('js/onsenui.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js" defer></script>

    <script src="{{ asset('js/app.js') }}"></script>
      
    <script src="{{ asset('js/eventlistener.js') }}"></script>
      <script src="{{ asset('js/pdfmake/pdfmake.min.js') }}"></script>
      
  <script src="{{ asset('js/pdfmake/vfs_fonts.js') }}"></script>
     <!--
      
      <script src="{{ asset('js/Quikmodal.js') }}"></script>
      
      <script src="{{ asset('js/function.js') }}"></script>
-->
        
      
    @yield('page-script')
  </body>
</html>