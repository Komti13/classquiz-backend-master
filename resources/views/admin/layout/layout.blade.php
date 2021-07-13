<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    <title>ClassQuiz - {{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- BEGIN PLUGIN CSS -->
    <link href="{{ asset('assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
    <link href="{{ asset('assets/plugins/bootstrapv3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrapv3/css/bootstrap-theme.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('assets/plugins/animate.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css"/>
    
    <!-- END PLUGIN CSS -->
@yield('css')
<!-- BEGIN CORE CSS FRAMEWORK -->
    <link href="{{ asset('webarch/css/webarch.css') }}" rel="stylesheet" type="text/css"/>

    <!-- END CORE CSS FRAMEWORK -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse ">
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="navbar-inner">
        <div class="header-seperation">
            <ul class="nav pull-left notifcation-center visible-xs visible-sm">
                <li class="dropdown">
                    <a href="#main-menu" data-webarch="toggle-left-side">
                        <i class="material-icons">menu</i>
                    </a>
                </li>
            </ul>
            <!-- BEGIN LOGO -->
            <a href="/">
                <img src="{{ asset('assets/img/logo.png') }}" class="logo" alt=""
                     data-src="{{ asset('assets/img/logo.png') }}"
                     data-src-retina="{{ asset('assets/img/logo2x.png') }}" width="140" height="35"/>
            </a>
            <!-- END LOGO -->
        </div>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <div class="header-quick-nav">
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="pull-left">
                <ul class="nav quick-section">
                    <li class="quicklinks">
                        <a href="#" class="" id="layout-condensed-toggle">
                            <i class="material-icons">menu</i>
                        </a>
                    </li>
                </ul>
                <ul class="nav quick-section">
                    <li class="quicklinks"><span class="h-seperate"></span></li>
                    <li class="quicklinks">
                        <a href="#" class="" id="my-task-list" data-placement="bottom" data-content=''
                           data-toggle="dropdown" data-original-title="Notifications">
                            <i class="material-icons">notifications_none</i>
                            <span class="badge badge-important bubble-only"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div id="notification-list" style="display:none">
                <div style="width:300px">
                    <div class="notification-messages success">
                        <div class="iconholder">
                            <i class="icon-warning-sign"></i>Pending History

                        </div>
                        <div class="message-wrapper">
                            <div class="heading">
                                Server load limited
                            </div>
                            <div class="description">
                                Database server has reached its daily capicity
                            </div>
                            <div class="date pull-left">
                                2 mins ago
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- END TOP NAVIGATION MENU -->
            <!-- BEGIN CHAT TOGGLER -->
            <div class="pull-right">
                <div class="chat-toggler sm">
                    <div class="profile-pic">
                        <img src="{{ asset('assets/img/avatar_small.png') }}" alt=""
                             data-src="{{ asset('assets/img/avatar_small.png') }}"
                             data-src-retina="{{ asset('assets/img/avatar_small2x.png') }}" width="35" height="35"/>
                        <div class="availability-bubble online"></div>
                    </div>
                </div>
                <ul class="nav quick-section ">
                    <li class="quicklinks">
                        <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
                            <i class="material-icons">tune</i>
                        </a>
                        <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                            <li>
                                <a href="{{ route('admins.edit',['id'=>Auth::guard('admin')->user()->id]) }}"> {{ Auth::guard('admin')->user()->name }}</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();  document.getElementById('logout-form').submit();"><i
                                        class="material-icons">power_settings_new</i>&nbsp;&nbsp;Log Out</a>

                                {!! Form::open(['route' => 'logout','id'=>'logout-form','class'=>'hidden']) !!}
                                {!! Form::close() !!}
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- END CHAT TOGGLER -->
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar " id="main-menu">
        <!-- BEGIN MINI-PROFILE -->
        <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
        @if(Auth::guard('admin')->user()->id == 1) 
            <!-- BEGIN SIDEBAR MENU -->
                <p class="menu-title sm">Overview <span class="pull-right"><a href="javascript:;"><i
                                class="material-icons">add</i></a></span>
                </p>
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}"> <i class="material-icons">home</i> <span
                                class="title">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}"> <i class="material-icons">trending_up</i> <span
                                class="title">Statistics</span></a>
                    </li>
                    <li>
                        <a href="{{ route('logistics') }}"> <i class="material-icons">bar_chart</i> <span
                                class="title">Logistics Managment</span></a>
                    </li>
                </ul>
                <p class="menu-title sm">Store <span class="pull-right"><a href="javascript:;"><i
                                class="material-icons">add</i></a></span>
                </p>
                <ul>
                    <li class="start "><a href="#"><i class="material-icons">apps</i> <span class="title">Packs</span>
                            <span></span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('packs.index') }}"> Packs</a></li>
                            <li><a href="{{ route('pack-promotions.index') }}"> Promotions</a></li>
                            <li><a href="{{ route('tokens.index') }}"> Tokens</a></li>
                        </ul>
                    </li>
                    <li class="start "><a href="#"><i class="material-icons">money</i> <span
                                class="title">Payments</span>
                            <span></span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('payments.index') }}"> All</a></li>
                            <li><a href="{{ route('payments.index',['status'=>1]) }}"> Pending</a></li>
                            <li><a href="{{ route('payments.index', ['status'=>2]) }}"> Accepted</a></li>
                            <li><a href="{{ route('payments.index',['status'=>3]) }}"> Refused</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('subscriptions.index') }}"> <i class="material-icons">shop</i> <span
                                class="title">Subscriptions</span></a>
                    </li>
                </ul>
                <p class="menu-title sm">Content <span class="pull-right"><a href="javascript:;"><i
                                class="material-icons">add</i></a></span>
                </p>
                <ul>
                    <li>
                        <a href="{{ route('subjects.index') }}"> <i class="material-icons">subject</i> <span
                                class="title">Subjects</span></a>
                    </li>
                    <li class="start "><a href="#"><i class="material-icons">list</i> <span
                                class="title">Chapters</span>
                            <span></span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('chapters.index') }}"> Chapters</a></li>
                            <li><a href="{{ route('chapter-types.index') }}"> Chapter types</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('levels.index') }}"> <i class="material-icons">format_list_bulleted</i> <span
                                class="title">Levels</span></a>
                    </li>
                    <li>
                        <a href="{{ route('templates.index') }}"> <i class="material-icons">image</i> <span
                                class="title">Templates</span></a>
                    </li>
                    <li class="start "><a href="#"><i class="material-icons">format_shapes</i> <span
                                class="title">Icons</span> <span></span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('icons.index') }}"> Icons</a></li>
                            <li><a href="{{ route('atlases.index') }}"> Atlases</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('questions.index') }}"> <i class="material-icons">help</i> <span
                                class="title">Questions</span></a>
                    </li>
                </ul>
            @endif
            <p class="menu-title sm">User <span class="pull-right"><a href="javascript:;"><i
                            class="material-icons">add</i></a></span></p>
            <ul>


                <li class="start "><a href="#"><i class="material-icons">supervised_user_circle</i> <span
                            class="title">Users</span>
                        <span></span> <span class="arrow "></span> </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('users.index',['role'=>'SCHOOL_ADMIN']) }}"> School Administrators</a>
                        </li>
                        <li><a href="{{ route('users.index',['role'=>'STUDENT']) }}"> Students</a></li>
                        <li><a href="{{ route('users.index',['role'=>'PARENT']) }}"> Parents</a></li>
                        <li><a href="{{ route('users.index',['role'=>'TEACHER']) }}"> Teachers</a></li>
                        <li><a href="{{ route('users.index',['role'=>'EDITOR']) }}"> Editors</a></li>
                        <li><a href="{{ route('users.index',['role'=>'MARKETER']) }}"> Marketers</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"> <i class="material-icons">search</i> <span
                            class="title">Users Log</span></a>
                </li>
            </ul>
            @if(Auth::guard('admin')->user()->id ==1)
                <p class="menu-title sm">Config <span class="pull-right"><a href="javascript:;"><i
                                class="material-icons">add</i></a></span>
                </p>
                <ul>
                    <li class="start "><a href="#"><i class="material-icons">settings</i> <span
                                class="title">Settings</span> <span></span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('logs') }}"> Logs</a></li>
                            <li><a href="{{ route('payment-methods.index') }}"> Payment Methods</a></li>
                            <li><a href="{{ route('schools.index') }}"> Schools</a></li>
                            <li><a href="{{ route('admins.index') }}"> Admins</a></li>
                            <li><a href="{{ route('countries.index') }}"> Countries</a></li>
                            <li><a href="{{ route('governorates.index') }}"> Governorates</a></li>
                            <li><a href="{{ route('delegations.index') }}"> Delegations</a></li>
                            <li><a href="{{ route('avatars.index') }}"> Avatars</a></li>
                        </ul>
                    </li>

                </ul>
            @endif
            <div class="clearfix"></div>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <a href="#" class="scrollup">Scroll</a>
    <!-- END SIDEBAR -->
    <!-- BEGIN PAGE CONTAINER-->
    <div class="page-content">
        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <div id="portlet-config" class="modal hide">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button"></button>
                <h3>Widget Settings</h3>
            </div>
            <div class="modal-body"> Widget settings form goes here</div>
        </div>
        <div class="clearfix"></div>
        <div class="content">
            <div class="page-title">
                <h3>{{ $title }}</h3>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success mt-3">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    </button>
                    <p>{{ $message }}</p>

                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger mt-3">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    </button>
                    <p>{{ $message }}</p>

                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    </button>
                    <strong>You have some errors</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>
<!-- END CONTAINER -->
<!-- END CONTAINER -->
<script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
<!-- BEGIN JS DEPENDECENCIES-->
<script src="{{ asset('assets/plugins/jquery/jquery-1.11.3.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrapv3/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-block-ui/jqueryblockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-unveil/jquery.unveil.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>

<!-- END CORE JS DEPENDECENCIES-->
<!-- BEGIN CORE TEMPLATE JS -->
<script src="{{ asset('webarch/js/webarch.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/chat.js') }}" type="text/javascript"></script>
<!-- END CORE TEMPLATE JS -->
@yield('js')
</body>
</html>
