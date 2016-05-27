@extends('main')
    @section('page-styles')
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        {{ HTML::style('/assets/metronic/global/plugins/font-awesome/css/font-awesome.min.css') }}
        {{ HTML::style('/assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css') }}
        {{ HTML::style('/assets/metronic/global/plugins/bootstrap/css/bootstrap.min.css') }}
        {{ HTML::style('/assets/metronic/global/plugins/uniform/css/uniform.default.css') }}
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME STYLES -->
        {{ HTML::style('/assets/metronic/global/css/components.css') }}
        <!-- END THEME STYLES -->    
    
        {{ HTML::style('/assets/metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
        {{ HTML::style('/assets/metronic/global/css/plugins.css') }}
        
        {{ HTML::style('/assets/metronic/admin/layout/css/layout.css') }}
        {{ HTML::style('/assets/metronic/admin/layout/css/themes/blue.css') }}
        {{ HTML::style('/assets/metronic/admin/layout/css/custom.css') }}
        {{ HTML::style('/assets/css/style_admin.css') }}
    @stop

    @section('body')
        <body class="page-header-fixed page-quick-sidebar-over-content">
            @section('header')
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="{{ URL::route('admin.dashboard') }}">
                            <img src="/assets/img/logo.png" alt="logo" class="logo-default" style="height: 32px; margin-top: 10px;">
                        </a>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->

                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        @if (Session::has('admin_id'))
                        <ul class="nav navbar-nav pull-right">
                            <li class="dropdown dropdown-quick-sidebar-toggler">
    					        <a href="#" class="dropdown-toggle">
					                {{ Session::get('admin_name') }}
    					        </a>
    				        </li>                        
                            <li class="dropdown dropdown-quick-sidebar-toggler">
                                <a href="{{ URL::route('admin.auth.logout') }}" class="dropdown-toggle">
                                    <i class="icon-logout"></i> Sign Out
                                </a>
                            </li>
                        </ul>
                        @endif
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <div class="clearfix"></div>
            @show

            @section('main')
            <?php if (!isset($pageNo)) { $pageNo = 1; } ?>
            <div class="page-container">
                <div class="page-sidebar-wrapper">
                    <div class="page-sidebar navbar-collapse collapse">
                        <ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
                            <li class="sidebar-toggler-wrapper">
                                <div class="sidebar-toggler"></div>
                            </li>
                            <li class="start {{ ($pageNo == 1) ? 'active' : '' }}">
                                <a href="#">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Dashboard</span>
                                </a>
                            </li>
                            <li class="{{ ($pageNo == 3) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.company') }}">
                                    <i class="fa fa-building"></i>
                                    <span class="title">Company Management</span>
                                </a>
                            </li>
                            <li class="{{ ($pageNo == 12) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.store') }}">
                                    <i class="fa fa-bank"></i>
                                    <span class="title">Store Management</span>
                                </a>
                            </li>
                            <li class="{{ ($pageNo == 2) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.user') }}">
                                    <i class="icon-users"></i>
                                    <span class="title">User Management</span>
                                </a>
                            </li>
                            <li class="{{ ($pageNo == 4) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.city') }}">
                                    <i class="fa fa-map-marker"></i>
                                    <span class="title">City Management</span>
                                </a>
                            </li>                            
                            <li class="{{ ($pageNo == 5) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.category') }}">
                                    <i class="fa fa-tag"></i>
                                    <span class="title">Category Management</span>
                                </a>
                            </li>
                            <!-- li class="{{ ($pageNo == 6) ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-comments"></i>
                                    <span class="title">Comments Management</span>
                                </a>
                            </li -->
                            <!-- li class="{{ ($pageNo == 7) ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-star"></i>
                                    <span class="title">Feedback Management</span>
                                </a>
                            </li -->
                            
                            <li class="{{ ($pageNo == 8) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.offer') }}">
                                    <i class="fa fa-gavel"></i>
                                    <span class="title">Offer Management</span>
                                </a>
                            </li>
                            
                            <li class="{{ ($pageNo == 9) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.loyalty') }}">
                                    <i class="fa fa-heart"></i>
                                    <span class="title">Loyalty Management</span>
                                </a>
                            </li>
                            
                            <!-- li class="{{ ($pageNo == 10) ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-gear"></i>
                                    <span class="title">Settings</span>
                                </a>
                            </li -->
                            
                            <li class="{{ ($pageNo == 11) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.plan') }}">
                                    <i class="icon-envelope-open"></i>
                                    <span class="title">Plan Management</span>
                                </a>
                            </li>
                            
                            <li class="{{ ($pageNo == 13) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.setting') }}">
                                    <i class="fa fa-cog"></i>
                                    <span class="title">Setting Management</span>
                                </a>
                            </li>
                            
                            <li class="{{ ($pageNo == 14) ? 'active' : '' }}">
                                <a href="{{ URL::route('admin.subscriber') }}">
                                    <i class="fa fa-cog"></i>
                                    <span class="title">Newsletter Subscriber</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="page-content-wrapper">
                    <div class="page-content">
                        @yield('breadcrumb')
                        @yield('content')
                    </div>
                </div>
            </div>
            @show

            @section('footer')
                <div class="page-footer footer-background">
                    <div class="page-footer-inner">
                         &copy; Copyright 2015 | All Rights Reserved | Powered by Finternet-Group
                    </div>
                    <div class="page-footer-tools">
                        <span class="go-top">
                        <i class="fa fa-angle-up"></i>
                        </span>
                    </div>
                </div>
            @show
        </body>
    @stop

    @section('page-scripts')
        {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
        {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js') }}
        
        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
        <!-- BEGIN CORE PLUGINS -->
        <!--[if lt IE 9]>
        {{ HTML::script('/assets/metronic/global/plugins/respond.min.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/excanvas.min.js') }}
        <![endif]-->
        {{ HTML::script('/assets/metronic/global/plugins/jquery-1.11.0.min.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/jquery-migrate-1.2.1.min.js') }}
        <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
        {{ HTML::script('/assets/metronic/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/bootstrap/js/bootstrap.min.js') }}
        <!-- END CORE PLUGINS -->
            
        {{ HTML::script('/assets/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/jquery.blockui.min.js') }}    
        {{ HTML::script('/assets/metronic/global/plugins/jquery.cokie.min.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/uniform/jquery.uniform.min.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}    
    
        {{ HTML::script('/assets/metronic/global/scripts/metronic.js') }}
        {{ HTML::script('/assets/metronic/admin/layout/scripts/layout.js') }}
        {{ HTML::script('/assets/metronic/admin/layout/scripts/quick-sidebar.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/bootbox/bootbox.min.js') }}
        <script>
        jQuery(document).ready(function() {       
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            QuickSidebar.init() // init quick sidebar

            $("a#js-a-delete").click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                bootbox.confirm("Are you sure?", function(result) {
                    if (result) {
                        window.location.href = url;
                    }
                });
            });
        });
        </script>        
    @stop
@stop
