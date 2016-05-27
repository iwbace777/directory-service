@extends('main')
    @section('page-styles')
        {{ HTML::style('/assets/metronic/admin/layout/css/layout.css') }}
        {{ HTML::style('/assets/metronic/admin/layout/css/themes/default.css') }}
        {{ HTML::style('/assets/metronic/admin/layout/css/custom.css') }}
        {{ HTML::style('/assets/css/style_company.css') }}
    @stop

    @section('body')
        <body class="page-header-fixed page-quick-sidebar-over-content">
            @section('header')
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="{{ URL::route('user.home') }}">
                            <img src="/assets/img/logo_company.png" alt="logo" class="logo-default" style="height: 32px; margin-top: 10px;">
                        </a>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->

                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">                    
                        <ul class="nav navbar-nav pull-right">
                            @if (Session::has('company_id'))
                            <li class="dropdown dropdown-quick-sidebar-toggler">
    					        <a href="#" class="dropdown-toggle">
					                {{ Session::get('company_name') }}
    					        </a>
    				        </li>                        
                            <li class="dropdown dropdown-quick-sidebar-toggler">
                                <a href="{{ URL::route('company.auth.logout') }}" class="dropdown-toggle">
                                    <i class="icon-logout"></i> Sign Out
                                </a>
                            </li>
                            @else
                            <li class="dropdown dropdown-quick-sidebar-toggler {{ ($pageNo == 51) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.auth.login') }}" class="dropdown-toggle">
                                    <i class="icon-login"></i>
                                    Sign In
                                </a>
                            </li>
                            <li class="dropdown dropdown-quick-sidebar-toggler {{ ($pageNo == 52) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.auth.signup') }}" class="dropdown-toggle">
                                    <i class="icon-note"></i>
                                    Register
                                </a>
                            </li>
                            @endif
                        </ul>                        
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <div class="clearfix"></div>
            @show

            @section('main')
            <?php if (!isset($pageNo)) { $pageNo = 0; } ?>
            <div class="page-container">
                <div class="page-sidebar-wrapper">
                    <div class="page-sidebar navbar-collapse collapse">
                        <ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
                            <li class="sidebar-toggler-wrapper">
                                <div class="sidebar-toggler"></div>
                            </li>
                            
                            <li class="start {{ ($pageNo == 1) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.dashboard') }}">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Dashboard</span>
                                </a>
                            </li>
                            
                            <li class="{{ ($pageNo == 13) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.store') }}">
                                    <i class="fa fa-bank"></i>
                                    <span class="title">Store Management</span>
                                </a>
                            </li>                            
                            
                            <li class="{{ ($pageNo == 2) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.user') }}">
                                    <i class="icon-users"></i>
                                    <span class="title">Consumer Management</span>
                                </a>
                            </li>

                            <!-- li class="{{ ($pageNo == 3) ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-cubes"></i>
                                    <span class="title">Marketing Tools</span>
                                </a>
                            </li -->
                            <!-- li class="{{ ($pageNo == 4) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.comment') }}">
                                    <i class="fa fa-comments"></i>
                                    <span class="title">Comment Management</span>
                                </a>
                            </li -->
                            <li class="{{ ($pageNo == 11) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.ratingType') }}">
                                    <i class="fa fa-bookmark"></i>
                                    <span class="title">Rating Type Management</span>
                                </a>
                            </li>
                            <!-- li class="{{ ($pageNo == 5) ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-star"></i>
                                    <span class="title">Rating Management</span>
                                </a>
                            </li -->
                            <li class="{{ ($pageNo == 6) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.feedback') }}">
                                    <i class="fa fa-edit"></i>
                                    <span class="title">Feedback Management</span>
                                </a>
                            </li>
                            
                            <li class="{{ ($pageNo == 14) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.message') }}">
                                    <i class="fa fa-comment"></i>
                                    <span class="title">Message Management</span>
                                </a>
                            </li>                            
                            
                            <li class="{{ ($pageNo == 7) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.offer') }}">
                                    <i class="fa fa-gavel"></i>
                                    <span class="title">Offer Management</span>
                                </a>
                            </li>
                            
                            <li class="{{ ($pageNo == 8) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.loyalty') }}">
                                    <i class="fa fa-heart"></i>
                                    <span class="title">Loyalty Management</span>
                                </a>
                            </li>
                            
                            <li class="{{ ($pageNo == 12) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.subscribe') }}">
                                    <i class="fa fa-trophy"></i>
                                    <span class="title">Subscribe Management</span>
                                </a>
                            </li>                            
                            
                            <li class="{{ ($pageNo == 10) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.widget.index') }}">
                                    <i class="fa fa-gear"></i>
                                    <span class="title">Widgets</span>
                                </a>
                            </li>
                            
                            <li class="last {{ ($pageNo == 9) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.profile') }}">
                                    <i class="fa fa-building"></i>
                                    <span class="title">Company Profile</span>
                                </a>
                            </li>
                            
                            <li class="{{ ($pageNo == 15) ? 'active' : '' }}">
                                <a href="{{ URL::route('company.contact') }}">
                                    <i class="fa fa-envelope"></i>
                                    <span class="title">Contact Us</span>
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
        {{ HTML::script('/assets/metronic/admin/layout/scripts/layout.js') }}
        {{ HTML::script('/assets/metronic/admin/layout/scripts/quick-sidebar.js') }}
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

            $('[data-toggle="tooltip"]').tooltip();
        });
        </script>        
    @stop
@stop
    