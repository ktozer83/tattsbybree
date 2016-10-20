<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('page_title')| Tatts by Bree</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

        <!-- Styles -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/style.css" type="text/css" rel="stylesheet">

        @yield('additional_css')
    </head>
    <body>
        <noscript>
        <div id="noscriptOverlay">
            <p>Please enable javascript in order to use this site.</p>
        </div>
        </noscript>
        <div id="wrapper" class="container">

            <!-- top navbar start -->
            <nav class="navbar navbar-default">
                <div id="topNav" class="container-fluid">
                    <div class="navbar-header">
                        <!-- Collapsed Hamburger -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!-- Authentication Links -->

                        <ul class="nav-left list-inline">
                            @if (Auth::guest())
                            <li><a @if (Request::path() == 'login') class="currentPage" @endif href="/login">Login</a></li>
                            <li><a @if (Request::path() == 'register') class="currentPage" @endif href="/register">Register</a></li>
                            @else
                            <li class="dropdown list-unstyled user-info">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }}<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    @if (Auth::user()->account_type_id <= '2')
                                    <li><a href="/members/admin/appointments">View All Appointments</a></li>
                                    <li><a href="/members/admin/booking">Edit Booking Status</a></li>
                                    <li><a href="/members/admin/categories">Edit Categories</a></li>
                                    <li><a href="/members/admin/images">Edit Portfolio Images</a></li>
                                    @endif
                                    @if ((Auth::user()->account_type_id == '1') or (Auth::user()->account_type_id == '3'))
                                    <li><a href="{{ url('/members') }}">View Appointments</a></li>
                                    <li><a href="{{ url('/members/quote') }}">Get a Quote</a></li>
                                    @endif
                                    <li><a href="{{ url('/members/settings') }}">Account Settings</a>
                                    <li><a href="{{ url('/logout') }}">Logout</a></li>
                                </ul>
                            </li>
                            <li><a @if (Request::path() == 'members') class="currentPage" @endif href="/members">Members Home</a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">                    
                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <li><a @if (Request::path() == '/') class="currentPage" @endif href="{{ url('/') }}">Home</a></li>
                            <li><a @if (Request::path() == 'about') class="currentPage" @endif href="{{ url('/about') }}">About</a></li>
                            <li><a @if (Request::path() == 'portfolio') class="currentPage" @endif href="{{ url('/portfolio') }}">Portfolio</a></li>
                            <li><a @if (Request::path() == 'faq') class="currentPage" @endif href="{{ url('/faq') }}">FAQ</a></li>
                            <li><a @if (Request::path() == 'contact') class="currentPage" @endif href="{{ url('/contact') }}">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- top navbar end -->
            <!-- content container start -->
            <div id="content" class="container-fluid">
                <div id="header" class="row">
                    <div class="col-md-12">
                        <a id="headerText" href="/">Tatts by Bree</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('common.error')
                        @include('common.success')
                        @if (Request::path() != '/')
                        <div id="page-title" class="text-center col-sm-10 col-md-10 col-sm-offset-1 col-md-offset-1 sverige">
                            <h2>@yield('page_title')</h2>
                        </div>
                        @endif
                        @yield('content')
                    </div>
                </div>
                <div class="row">
                    <div id="contact-footer" class="col-md-12">
                        <ul class="list-inline">
                            <li><span class="glyphicon glyphicon-earphone"></span> (705)874-1520</li>
                            <li class="pull-right"><span class="glyphicon glyphicon-envelope"></span> inquiries@tattsbybree.com</li>
                        </ul> 
                    </div>
                </div>
            </div>
            <!-- content container end -->
            <!-- footer navbar start -->
            <nav class="navbar navbar-default">
                <div id="footer" class="container-fluid">

                    <a href="https://www.facebook.com/tattsbybree" target="_blank"><img class="socialImg" src="/img/f-Logo.png" alt="Tatts by Bree on Facebook" title="Tatts by Bree on Facebook"></a>
                    <a href="https://www.instagram.com/tattsbybree/" target="_blank"><img class="socialImg" src="/img/inst_logo.png" alt="Tatts by Bree on Instagram" title="Tatts by Bree on Instagram"></a>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <li><a @if (Request::path() == '/') class="currentPage" @endif href="{{ url('/') }}">Home</a></li>
                        <li><a @if (Request::path() == 'about') class="currentPage" @endif href="{{ url('/about') }}">About</a></li>
                        <li><a @if (Request::path() == 'portfolio') class="currentPage" @endif href="{{ url('/portfolio') }}">Portfolio</a></li>
                        <li><a @if (Request::path() == 'faq') class="currentPage" @endif href="{{ url('/faq') }}">FAQ</a></li>
                        <li><a @if (Request::path() == 'contact') class="currentPage" @endif href="{{ url('/contact') }}">Contact</a></li>
                    </ul>
                </div>
            </nav>

        </div>
        <!-- footer navbar end -->
        <!-- JavaScripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        @yield('additional_js')
        <script type="text/javascript">
(function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
        (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

ga('create', 'UA-55017432-3', 'auto');
ga('send', 'pageview');

        </script>
    </body>
</html>
