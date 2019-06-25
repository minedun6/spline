<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', app_name())</title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'Laravel 5 Boilerplate')">
        <meta name="author" content="@yield('meta_author', 'Peaksource')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')
        {{ Html::style(elixir('css/frontend.css')) }}
        @yield('after-styles')
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
        <link href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.formvalidation/0.6.1/css/formValidation.min.css">
        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
        <style>
          [v-cloak] {
            display: none;
          }
        </style>
        @if (Auth::guest())
         <style>
            .login {
              background-color: #2b3643 !important; }

            .login .logo {
              margin: 60px auto 20px auto;
              padding: 15px;
              text-align: center; }

            .login .content {
              background-color: #fff;
              width: 360px;
              margin: 0 auto;
              margin-bottom: 0px;
              padding: 30px;
              padding-top: 20px;
              padding-bottom: 15px;
              -webkit-border-radius: 7px;
              -moz-border-radius: 7px;
              -ms-border-radius: 7px;
              -o-border-radius: 7px;
              border-radius: 7px; }

            .login .content h3 {
              color: #000; }

            .login .content h4 {
              color: #555; }

            .login .content p {
              color: #222; }

            .login .content .login-form,
            .login .content .forget-form {
              padding: 0px;
              margin: 0px; }

            .login .content .input-icon {
              border-left: 2px solid #44B6AE !important; }

            .login .content .input-icon {
              -webkit-border-radius: 4px;
              -moz-border-radius: 4px;
              -ms-border-radius: 4px;
              -o-border-radius: 4px;
              border-radius: 4px; }
              .login .content .input-icon .form-control {
                border-left: 0; }

            .login .content .forget-form {
              display: none; }

            .login .content .register-form {
              display: none; }

            .login .content .form-title {
              font-weight: 300;
              margin-bottom: 25px; }

            .login .content .form-actions {
              background-color: #fff;
              clear: both;
              border: 0px;
              border-bottom: 1px solid #eee;
              padding: 0px 30px 25px 30px;
              margin-left: -30px;
              margin-right: -30px; }

            .login .content .form-actions .checkbox {
              margin-left: 0;
              padding-left: 0; }

            .login .content .forget-form .form-actions {
              border: 0;
              margin-bottom: 0;
              padding-bottom: 20px; }

            .login .content .register-form .form-actions {
              border: 0;
              margin-bottom: 0;
              padding-bottom: 0px; }

            .login .content .form-actions .checkbox {
              margin-top: 8px;
              display: inline-block; }

            .login .content .form-actions .btn {
              margin-top: 1px; }

            .login .content .forget-password {
              margin-top: 25px; }

            .login .content .create-account {
              border-top: 1px dotted #eee;
              padding-top: 10px;
              margin-top: 15px; }

            .login .content .create-account a {
              display: inline-block;
              margin-top: 5px; }

            /* select2 dropdowns */
            .login .content .select2-container {
              border-left: 2px solid #44B6AE !important; }

            .login .content .select2-container .select2-choice {
              border-left: none !important; }

            .login .content .select2-container i {
              display: inline-block;
              position: relative;
              color: #ccc;
              z-index: 1;
              top: 1px;
              margin: 4px 4px 0px -1px;
              width: 16px;
              height: 16px;
              font-size: 16px;
              text-align: center; }

            .login .content .has-error .select2-container i {
              color: #b94a48; }

            .login .content .select2-container a span {
              font-size: 13px; }

            .login .content .select2-container a span img {
              margin-left: 4px; }

            /* footer copyright */
            .login .copyright {
              text-align: center;
              margin: 0 auto;
              padding: 10px;
              color: #999;
              font-size: 13px; }

            @media (max-width: 480px) {
              /***
              Login page
              ***/
              .login .logo {
                margin-top: 10px; }
              .login .content {
                width: 280px; }
              .login .content h3 {
                font-size: 22px; }
              .login .checkbox {
                font-size: 13px; } }
            </style>
        @endif
    </head>
    @if (Auth::guest())
    <body class="login">
        <div class="logo">
 
        </div>
        <div class="content">
            @yield('content')
        </div>
    @else
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-md">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="/">
                        <img src="/img/logo.png" alt="logo" class="logo-default" /> </a>
                    <div class="menu-toggler sidebar-toggler"> </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        {{-- <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-bell"></i>
                                <span class="badge badge-default"> 7 </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <h3>
                                        <span class="bold">12 pending</span> notifications</h3>
                                    <a href="/account">view all</a>
                                </li>
                                <li>
                            </ul>
                        </li> --}}
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <span class="username username-hide-on-mobile"> 
                                {{ $logged_in_user->name }} {{ $logged_in_user->lastname }}
                                </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="/account">
                                        <i class="icon-user"></i> Mon profile </a>
                                </li>
                                @role('Administrator')
                                <li>
                                    <a href="/admin/dashboard">
                                        <i class="icon-settings"></i> Administration </a>
                                </li>
                                @endauth
                                <li>
                                    <a href="/logout">
                                        <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <div class="page-container">
            @include('frontend.includes.nav')
             <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    @include('includes.partials.messages')
                    @yield('content')
                </div>
                <!-- END CONTENT BODY -->
            </div>
        </div>
        <div class="page-footer">
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>

        <!-- Scripts -->
        @yield('before-scripts')
        {!! Html::script(elixir('js/frontend.js')) !!}
        @yield('after-scripts')
<script src="https://cdn.jsdelivr.net/jquery.formvalidation/0.6.1/js/formValidation.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.formvalidation/0.6.1/js/framework/bootstrap.min.js"></script>
        <script>
          $('ul.nav li.dropdown').hover(function() {
              if (!$('.navbar-toggle').is(':visible')) {
                  $(this).toggleClass('open', true);
              }
          }, function() {
              if (!$('.navbar-toggle').is(':visible')) {
                  $(this).toggleClass('open', false);
              }
          });
          $('ul.nav li.dropdown a').click(function(){
              if (!$('.navbar-toggle').is(':visible') && $(this).attr('href') != '#') {
                  $(this).toggleClass('open', false);
                  window.location = $(this).attr('href')
              }
          });
        </script>
        @include('includes.partials.ga')
    </body>
@endif
</html>